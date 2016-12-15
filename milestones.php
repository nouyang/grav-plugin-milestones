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
    protected $enable = false;
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

    public function onTwigSiteVariables() {
        $this->grav['twig']->milestones = $this->fetchMilestones();
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
            'onTwigSiteVariables' => ['onTwigSiteVariables', 0],
            'onTwigTemplatePaths' => ['onTwigTemplatePaths', 0],
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
                  'date' => date('D, d M Y H:i:s', time()),
                  'text' => $milestones,
                ];
              } else { 
                $data = array(
                  'name' => $name,
                  'milestones' => array([
                    'date' => date('D, d M Y H:i:s', time()),
                    'text' => $milestones,
                  ])
                );
              }
            // stores in /user/data/milestones/$name.yaml
              $file->save(Yaml::dump($data));

              break;
      }

    }

    /**
      * Return all files in directory 
      * Todo: add recursive
     */
    private function getFiles() {
      $files = [];
      $path = DATA_DIR . 'milestones';
      $files = array_diff(scandir($path), array('..', '.'));
      return $files;
    }

    /**
     * Return all comments
     * Add names to distinguish comments
     */
    private function fetchMilestones(){
      $files = $this->getFiles();
      $milestones = [];
      $all_milestones = [];
      //dump($files);

      foreach($files as $file) {
        $fileInstance = File::instance(DATA_DIR . 'milestones/' . $file);
        if ($fileInstance->content()) {
          $data = Yaml::parse($fileInstance->content());

          for ($i = 0; $i < count($data['milestones']); $i++) {
            //Timestamp in English
            $commentTimestamp = \DateTime::createFromFormat('D, d M Y H:i:s', $data['milestones'][$i]['date'])->getTimestamp();
            $data['milestones'][$i]['author'] = $data['name'];
            //If there are milestones, add them to the $all_milestones
            if (count($data['milestones'])) {
                $all_milestones = array_merge($all_milestones, $data['milestones']);
            }
          }
         }
      }

      return $all_milestones;
    }

    /**
     * Add templates directory to twig lookup paths.
     */
    public function onTwigTemplatePaths()
    {
        $this->grav['twig']->twig_paths[] = __DIR__ . '/templates';
    }



}
