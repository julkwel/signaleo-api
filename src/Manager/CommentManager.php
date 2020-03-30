<?php
/**
 * Julien Rajerison <julienrajerison5@gmail.com>
 **/

namespace App\Manager;

use App\Entity\Actualite;
use App\Entity\Comment;
use App\Utils\HtmlToEmoji;

/**
 * Class CommentManager.
 */
class CommentManager extends AbstractManager
{
    /**
     * @param Actualite $actualite
     *
     * @return array
     */
    public function handleComments(Actualite $actualite)
    {
        $comms = [];

        /** @var Comment $data */
        foreach ($actualite->getComments()->getValues() as $key => $data) {
            $comms[$key]['id'] = $data->getId();
            $comms[$key]['responses'] = $this->handleReplyComment($data);
            $comms[$key]['comment'] = HtmlToEmoji::convertTextToEmoji($data->getComment());
            $comms[$key]['user']['id'] = $data->getUser()->getId();
            $comms[$key]['user']['name'] = $data->getUser()->getName() ?? 'Signaleo';
            $comms[$key]['user']['gender'] = $data->getUser()->getGender() ?? 'Lahy';
            $comms[$key]['date'] = $data->getDateAdd()->format("d-m-Y H:i");
        }

        return array_reverse($comms);
    }

    /**
     * @param Comment $comment
     *
     * @return array
     */
    public function handleReplyComment(Comment $comment)
    {
        $replys = [];
        foreach ($comment->getComments()->getValues() as $key => $response) {
            $replys[$key]['id'] = $response->getId();
            $replys[$key]['comment'] = HtmlToEmoji::convertTextToEmoji($response->getComment());
            $replys[$key]['user']['name'] = $response->getUser()->getName() ?? 'Signaleo';
            $replys[$key]['user']['id'] = $response->getUser()->getId();
            $replys[$key]['user']['gender'] = $response->getUser()->getGender() ?? 'Lahy';
            $replys[$key]['date'] = $response->getDateAdd()->format("d-m-Y H:i");
        }

        return $replys;
    }
}
