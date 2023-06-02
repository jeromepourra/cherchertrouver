<?php

namespace Models;

use Model;
use ModelException;
use PDO;

class Messages extends Model {

    public function __construct() {
        $this->connect();
        $this->setTable("messages");
    }

    public function getAllFromConversation($sConversationId) {
        return $this->read("*", "conversation_id = :conversationId", "date DESC", [":conversationId" => $sConversationId]);
    }

    public function getFromId($sId) {
        return $this->read("*", "_id = :id", null, [":id" => $sId], "fetch");
    }

    public function getCountFromConversation($sConversationId) {
        $aResults = $this->read("COUNT(*)", "conversation_id = :conversationId", null, [":conversationId" => $sConversationId], "fetch", PDO::FETCH_NUM);
        return (int) $aResults[0];
    }

    public function getUnreadFromConversation($sConversationId, $sUserId) {
        $aResults = $this->read("COUNT(*)", "conversation_id = :conversationId AND unread = :unread AND NOT (user_id = :userId)", null, [":conversationId" => $sConversationId, ":unread" => true, ":userId" => $sUserId], "fetch", PDO::FETCH_NUM);
        return (int) $aResults[0];
    }

    public function create($aValues) {

        $aColumns = ["conversation_id", "user_id", "content", "date"];

        if (count($aColumns) === count($aValues)) {

            $sColumns = join(",", $aColumns);
            $sValues = ":" . join(",:", $aColumns);
            $aBind = [];

            foreach ($aColumns as $nIndex => $sColumn) {
                $aBind += [":" . $sColumn => $aValues[$nIndex]];
            }
    
            $this->insert($sColumns, $sValues, $aBind);

        } else {
            throw new ModelException(__METHOD__, "number of columns does not match number of values");
        }

    }

    public function updateUnread($sId) {
        $this->update("unread = :unread", "_id = :id", [":unread" => 0, ":id" => $sId]);
    }

}

?>