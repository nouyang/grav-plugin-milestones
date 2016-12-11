<?php
namespace Grav\Plugin;

use Grav\Common\Plugin;
use RocketTheme\Toolbox\Event\Event;
use Symfony\Component\Yaml\Yaml;
use RocketTheme\Toolbox\File\File;

/**
 * Class MilestonesPlugin
 * @package Grav\Plugin
 */
class MilestonesPlugin extends Plugin
{
    /**
     * @return array
     *
     * The getSubscribedEvents() gives the core a list of events
     *     that the plugin wants to listen to. The key of each
     *     array section is the event that the plugin listens to
     *     and the value (in the form of an array) contains the
     *     callable (or function) as well as the priority. The
     *     higher the number the higher the priority.
     */
    public static function getSubscribedEvents()
    {
        return [
            'onPluginsInitialized' => ['onPluginsInitialized', 0]
        ];
    }

    /**
     * Initialize the plugin
     */
    public function onPluginsInitialized()
    {
        // Don't proceed if we are in the admin plugin
        if ($this->isAdmin()) {
            return;
        }

        // Enable the main event we are interested in
        $this->enable([
            //'onPageContentRaw' => ['onPageContentRaw', 0]
            'onFormProcessed' => ['onFormProcessed', 0],
        ]);
    }

     /**
     * Handle form processing instructions.
     *
     * @param Event $event
     */
    public function onFormProcessed(Event $event)
    {
        $form = $event['form'];
        $action = $event['action'];
        $params = $event['params'];

        switch ($action) {
          case 'milestones':
              $post = !empty($_POST) ? $_POST : [];

              //$this->grav['debugger']->addMessage('hi!!');
              //$this->grav['debugger']->addMessage($post['data']);
              //$this->grav['debugger']->addMessage($post['data']['name']);
              //dump($post);

              $name = filter_var(urldecode($post['data']['name']), FILTER_SANITIZE_STRING);
              $milestones = filter_var(urldecode($post['data']['milestones']), FILTER_SANITIZE_STRING);

              $filename = DATA_DIR . 'milestones/';
              //$this->grav['debugger']->addMessage($filename);
              $filename .= $name . '.yaml';
              $file = File::instance($filename);
              if (file_exists($filename)) {
                $data = Yaml::parse($file->content());

                $data['milestones'][] = [
                  'text' => $milestones,
                  'date' => date('D, d M Y H:i:s', time()),
                ];
              } else { 
                $data = array(
                  'name' => $name,
                  'milestones' => array([
                    'text' => $milestones,
                    'date' => date('D, d M Y H:i:s', time()),
                  ])
                );
              }
            // stores in /user/data/milestones/$name.yaml
              $file->save(Yaml::dump($data));

              break;
      }

    }

}
