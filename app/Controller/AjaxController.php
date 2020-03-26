<?php
class AjaxController extends AppController {
    public $components = array('Emailing', 'Image');
    var $uses = array('Game', 'Dungeon', 'Classe', 'Race', 'EventsCharacter', 'EventsRole', 'Character', 'Event', 'RaidsRole', 'EventsTemplate', 'EventsTemplatesRole');

    var $allowedImageExts = array("gif", "jpeg", "jpg", "png");

    function beforeFilter() {
        parent::beforeFilter();

        Configure::write('debug', 0);
        $this->layout = 'ajax';
        $this->autoRender = false;
    }

    function getListByGame() {
        if(!empty($this->request->query['game'])) {
            $gameId = $this->request->query['game'];

            $classesList = $this->Classe->find('list', array('conditions' => array('game_id' => $gameId), 'order' => 'title ASC'));
            $this->set('classesList', $classesList);

            $racesList = $this->Race->find('list', array('conditions' => array('game_id' => $gameId), 'order' => 'title ASC'));
            $this->set('racesList', $racesList);

            $rolesList = $this->RaidsRole->find('list', array('order' => 'title ASC'));
            $this->set('rolesList', $rolesList);

            $this->render('/Elements/char_form_elements');
        }

        return;
    }

    function setMainCharacter() {
        if(!empty($this->request->query['character'])) {
            $characterId = $this->request->query['character'];

            $params = array();
            $params['fields'] = array('Character.id', 'Character.game_id');
            $params['recursive'] = -1;
            $params['conditions']['Character.id'] = $this->request->query['character'];
            $params['conditions']['Character.user_id'] = $this->user['User']['id'];
            if(!$character = $this->Character->find('first', $params)) {
                return;
            }

            $this->Character->updateAll(array('Character.main' => 0), array('Character.game_id' => $character['Character']['game_id']));

            $toUpdate = array();
            $toUpdate['id'] = $character['Character']['id'];
            $toUpdate['main'] = 1;
            $this->Character->save($toUpdate);
        }

        return;
    }

    function getDungeonsByGame() {
        if(!empty($this->request->query['game'])) {
            $gameId = $this->request->query['game'];

            $dungeonsList = $this->Dungeon->find('all', array('fields' => array('id', 'title', 'level_required'), 'recursive' => -1, 'conditions' => array('game_id' => $gameId), 'order' => 'title ASC'));
            return json_encode($dungeonsList);
        }
    }

    function eventSignin() {
        $jsonMessage = array();

        if(!empty($this->request->query['u']) && !empty($this->request->query['e']) && isset($this->request->query['signin']) && !empty($this->request->query['character'])) {
            // Choosed character must be in the event level range
            $params = array();
            $params['fields'] = array('character_level', 'open', 'time_start', 'time_inscription');
            $params['recursive'] = -1;
            $params['conditions']['id'] = $this->request->query['e'];
            if(!$event = $this->Event->find('first', $params)) {
                $jsonMessage['type'] = 'important';
                $jsonMessage['msg'] = __('MushRaider can\'t find this event');
                return json_encode($jsonMessage);
            }

            if($event['Event']['time_start'] < date('Y-m-d H:i:s')) {
                $jsonMessage['type'] = 'important';
                $jsonMessage['msg'] = __('Too late ! This event is already started');
                return json_encode($jsonMessage);
            }

            if($event['Event']['time_inscription'] < date('Y-m-d H:i:s')) {
                $jsonMessage['type'] = 'important';
                $jsonMessage['msg'] = __('Too late ! Registrations for this event are closed');
                return json_encode($jsonMessage);
            }

            $params = array();
            $params['fields'] = array('Character.level', 'Character.title', 'Classe.*');
            $params['recursive'] = 1;
            $params['contain']['Classe'] = array();
            $params['conditions']['Character.id'] = $this->request->query['character'];
            $params['conditions']['Character.user_id'] = $this->user['User']['id'];
            $params['conditions']['Character.level >='] = $event['Event']['character_level'];
            if(!$character = $this->Character->find('first', $params)) {
                $jsonMessage['type'] = 'important';
                $jsonMessage['msg'] = __('Your character mush be above the level %s', $event['Event']['character_level']);
                return json_encode($jsonMessage);
            }

            // If event is not open, status must be 0 or 1
            if(!$event['Event']['open'] && $this->request->query['signin'] == 2) {
                $this->request->query['signin'] = 1;
            }elseif($event['Event']['open'] && $this->request->query['signin'] == 2) { // If the event is open, check if the role is full
                // Get role count
                $params = array();
                $params['recursive'] = -1;
                $params['fields'] = array('count');
                $params['conditions']['raids_role_id'] = $this->request->query['role'];
                $params['conditions']['event_id'] = $this->request->query['e'];
                if($eventRole = $this->EventsRole->find('first', $params)) {
                    $charsInRole = $this->EventsCharacter->find('count', array('recursive' => -1, 'conditions' => array('event_id' => $this->request->query['e'], 'raids_role_id' => $this->request->query['role'], 'status' => 2)));
                    if($eventRole['EventsRole']['count'] <= $charsInRole) {
                        $jsonMessage['type'] = 'important';
                        $jsonMessage['msg'] = __('This role is already full');
                        return json_encode($jsonMessage);
                    }
                }
            }

            $toSave = array();
            $toSave['event_id'] = $this->request->query['e'];
            $toSave['user_id'] = $this->request->query['u'];
            $toSave['character_id'] = !empty($this->request->query['character'])?$this->request->query['character']:null;
            $toSave['raids_role_id'] = !empty($this->request->query['role'])?$this->request->query['role']:null;
            $toSave['comment'] = trim(strip_tags($this->request->query['c']));
            $toSave['status'] = $this->request->query['signin'];
            if($this->EventsCharacter->__add($toSave)) {
                switch($toSave['status']) {
                    case 1:
                        $jsonMessage['type'] = 'info';
                        break;
                    case 2:
                        $jsonMessage['type'] = 'success';
                        break;
                    case 0:
                    default:
                        $jsonMessage['type'] = 'warning';
                        break;
                }                
                $jsonMessage['msg'] = 'ok';

                $rosterHtml = '<li data-id="'.$toSave['character_id'].'" data-user="'.$toSave['user_id'].'">';
                    $rosterHtml .= '<span class="character" style="color:'.$character['Classe']['color'].'">';
                        if(!empty($character['Classe']['icon'])) {
                            $rosterHtml .= '<img src="'.$character['Classe']['icon'].'" class="tt" title="'.$character['Classe']['title'].'" width="16" />';
                        }else {
                            $rosterHtml .= $character['Classe']['title'];
                        }
                        $rosterHtml .= ' '.$character['Character']['title'].' ('.$character['Character']['level'].')';
                    $rosterHtml .= '</span>';
                    if(!empty($toSave['comment'])) {
                        $rosterHtml .= '<span class="tt" title="'.$toSave['comment'].'"><span class="fa fa-comments-o"></span></span>';
                    }
                $rosterHtml .= '</li>';
                $jsonMessage['html'] = $rosterHtml;
            }else {
                $jsonMessage['type'] = 'important';
                $jsonMessage['msg'] = __('Error while adding your character');
            }
        }

        return json_encode($jsonMessage);
    }

    function roster() {
        if(isset($this->request->query['v']) && isset($this->request->query['refused']) && !empty($this->request->query['r']) && !empty($this->request->query['e'])) {
            $eventId = $this->request->query['e'];
            $roleId = str_replace('role_', '', $this->request->query['r']);
            $validatedList = explode(',', $this->request->query['v']);
            $refusedList = explode(',', $this->request->query['refused']);

            $params = array();
            $params['recursive'] = -1;
            $params['conditions']['id'] = $eventId;
            if(!$event = $this->Event->find('first', $params)) {
                return 'fail';
            }

            if(!($this->user['User']['can']['manage_own_events'] && $this->user['User']['id'] == $event['Event']['user_id']) && !$this->user['User']['can']['manage_events'] && !$this->user['User']['can']['full_permissions']) {
                return 'fail';
            }

            $params = array();
            $params['fields'] = array('id', 'character_id', 'last_notification');
            $params['recursive'] = 1;
            $params['contain']['User']['fields'] = array('email', 'notifications_validate');
            $params['conditions']['EventsCharacter.event_id'] = $eventId;
            $params['conditions']['EventsCharacter.raids_role_id'] = $roleId;
            $params['conditions']['EventsCharacter.status >'] = 0;
            if($eventCharacters = $this->EventsCharacter->find('all', $params)) {
                foreach($eventCharacters as $eventCharacter) {
                    $toSave = array();
                    $toSave['id'] = $eventCharacter['EventsCharacter']['id'];
                    if(in_array($eventCharacter['EventsCharacter']['character_id'], $refusedList)) {
                        $toSave['status'] = 3;    
                    }elseif(in_array($eventCharacter['EventsCharacter']['character_id'], $validatedList)) {
                        $toSave['status'] = 2;
                    }else {
                        $toSave['status'] = 1;
                    }                    

                    // If notifications are enable, send email to validated and refused users
                    if($eventCharacter['User']['notifications_validate'] && Configure::read('Config.notifications')->enabled) {
                        if($toSave['status'] == 2 && $eventCharacter['EventsCharacter']['last_notification'] != 2) {
                            $this->Emailing->eventValidate($eventCharacter['User']['email'], $event['Event']);
                            $toSave['last_notification'] = 2;
                        }elseif($toSave['status'] == 3 && $eventCharacter['EventsCharacter']['last_notification'] != 3) {
                            $this->Emailing->eventRefuse($eventCharacter['User']['email'], $event['Event']);
                            $toSave['last_notification'] = 3;
                        }
                    }

                    // Save
                    if(!$this->EventsCharacter->save($toSave)) {
                        return 'fail';
                    }
                }

                return 'ok';
            }
        }

        return 'fail';
    }

    function updateRosterChar() {
        if(isset($this->request->query['c']) && !empty($this->request->query['r']) && !empty($this->request->query['e'])) {
            $eventId = $this->request->query['e'];
            $roleId = str_replace('role_', '', $this->request->query['r']);
            $characterId = $this->request->query['c'];

            $params = array();
            $params['fields'] = array('user_id');
            $params['recursive'] = -1;
            $params['conditions']['id'] = $eventId;
            if(!$event = $this->Event->find('first', $params)) {
                return 'fail';
            }

            if(!($this->user['User']['can']['manage_own_events'] && $this->user['User']['id'] == $event['Event']['user_id']) && !$this->user['User']['can']['manage_events'] && !$this->user['User']['can']['full_permissions']) {
                return 'fail';
            }

            $params = array();
            $params['fields'] = array('id');
            $params['recursive'] = -1;
            $params['conditions']['event_id'] = $eventId;
            $params['conditions']['character_id'] = $characterId;
            if($eventCharacter = $this->EventsCharacter->find('first', $params)) {
                $toSave = array();
                $toSave['id'] = $eventCharacter['EventsCharacter']['id'];
                $toSave['raids_role_id'] = $roleId;
                if(!$this->EventsCharacter->save($toSave)) {
                    return 'fail';
                }

                return 'ok';
            }
        }

        return 'fail';
    }

    function getDefaultRole() {
        $jsonMessage = array();
        $jsonMessage['role'] = '';
        if(!empty($this->request->query['character'])) {
            $params = array();
            $params['fields'] = array('default_role_id');
            $params['recursive'] = -1;
            $params['conditions']['Character.id'] = $this->request->query['character'];
            if($character = $this->Character->find('first', $params)) {
                $jsonMessage['role'] = !empty($character['Character']['default_role_id'])?$character['Character']['default_role_id']:'';
            }
        }

        return json_encode($jsonMessage);
    }

    function copyEvent() {
        if($this->user['User']['can']['create_templates'] || $this->user['User']['can']['full_permissions']) {
            if(isset($this->request->query['e']) && !empty($this->request->query['name'])) {
                if($this->Event->copy($this->request->query['e'], $this->request->query['name'])) {
                    return 'ok';
                }
            }
        }

        return 'fail';
    }

    function loadTemplate() {
        $jsonMessage = array();
        $jsonMessage['type'] = 'error';
        $jsonMessage['msg'] = __('Error while loading the template');
        if(!empty($this->request->query['t'])) {
            // Load the template
            $params = array();
            $params['recursive'] = 1;
            $params['contain']['EventsTemplatesRole'] = array();
            $params['conditions']['id'] = $this->request->query['t'];
            if($eventTemplate = $this->EventsTemplate->find('first', $params)) {
                $jsonMessage['type'] = 'ok';
                $jsonMessage['msg'] = $eventTemplate;
            }
        }

        return json_encode($jsonMessage);
    }

    function filterEvents() {
        if(isset($this->request->query['game'])) {
            $gameId = $this->request->query['game'];
            $this->Cookie->write('filterEvents', $gameId, false, '+2 weeks');
        }

        return;
    }

    function export() {
        $url = Router::url('/', true).'export/events/'.$this->user['User']['calendar_key'];
        $url .= $this->request->query['game']?'/'.$this->request->query['game']:'';
        $url .= '.ics';

        return $url;
    }

    function getimages() {
        $response = array();
        $dir = new Folder('files/uploads');
        $images = $dir->find('.*\.('.implode('|', $this->allowedImageExts).')');
        if(!empty($images)) {
            foreach($images as $image) {
                array_push($response, $this->Tools->addBasePath('/files/uploads/'.$image));
            }
        }

        return stripslashes(json_encode($response));
    }

    function uploadimage() {
        $response = new StdClass;

        if(!empty($this->request->params['form']) && !empty($this->request->params['form']['img'])) {
            $ext = $this->Tools->getFileExt($this->request->params['form']['img']['name']);
            if(!in_array($ext, $this->allowedImageExts)) {
                $response->error = '.'.$ext.' '.__('extension is not allowed');
            }

            $imageName = $this->Image->__add($this->request->params['form']['img'], 'files/uploads', '');
            $response->link = $this->Tools->addBasePath($imageName['name']);
        }

        return stripslashes(json_encode($response));
    }

    function delimage() {
        if(!empty($this->request->data['src'])) {
            $imgPath = ltrim($this->Tools->removeBasePath($this->request->data['src']), '/');
            if(file_exists($imgPath)) {
                unlink($imgPath);
            }
        }
    }
}