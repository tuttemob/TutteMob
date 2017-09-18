<?php
namespace Application\Api;

class Facebook
{
    static public function getUser($accessToken)
    {
        $configFacebook = include './config/application.config.facebook.php';

        $fb = new \Facebook\Facebook([
            'app_id' => $configFacebook['app_id'],
            'app_secret' => $configFacebook['app_secret'],
            'default_graph_version' => 'v2.10'
        ]);

        try {
            $response = $fb->get('/me?fields=id,name,email', $accessToken);

            $me = $response->getGraphUser();

            if($me->getEmail()){
                return [
                    'id' => $me->getId(),
                    'name' => $me->getName(),
                    'email' => $me->getEmail(),
                ];
            }else{
                return false;
            }
        } catch(\Exception $e) {
            trigger_error($e->getMessage());
            return false;
        }
    }
}