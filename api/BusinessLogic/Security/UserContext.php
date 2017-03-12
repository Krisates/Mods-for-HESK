<?php

namespace BusinessLogic\Security;


class UserContext {
    /* @var $id int */
    public $id;

    /* @var $username string */
    public $username;

    /* @var $admin bool */
    public $admin;

    /* @var $name string */
    public $name;

    /* @var $email string */
    public $email;

    /* @var $signature string */
    public $signature;

    /* @var $language string|null */
    public $language;

    /* @var $categories int[] */
    public $categories;

    /* @var $permissions string[] */
    public $permissions;

    /* @var UserContextPreferences */
    public $preferences;

    /* @var UserContextNotifications */
    public $notificationSettings;

    /* @var $autoAssign bool */
    public $autoAssign;

    /* @var $ratingNegative int */
    public $ratingNegative;

    /* @var $ratingPositive int */
    public $ratingPositive;

    /* @var $rating float */
    public $rating;

    /* @var $totalNumberOfReplies int */
    public $totalNumberOfReplies;

    /* @var $active bool */
    public $active;

    /**
     * Builds a user context based on the current session. **The session must be active!**
     * @param $dataRow array the $_SESSION superglobal or the hesk_users result set
     * @return UserContext the built user context
     */
    static function fromDataRow($dataRow) {
        $userContext = new UserContext();
        $userContext->id = $dataRow['id'];
        $userContext->username = $dataRow['user'];
        $userContext->admin = $dataRow['isadmin'];
        $userContext->name = $dataRow['name'];
        $userContext->email = $dataRow['email'];
        $userContext->signature = $dataRow['signature'];
        $userContext->language = $dataRow['language'];
        $userContext->categories = explode(',', $dataRow['categories']);
        $userContext->permissions = explode(',', $dataRow['heskprivileges']);
        $userContext->autoAssign = $dataRow['autoassign'];
        $userContext->ratingNegative = $dataRow['ratingneg'];
        $userContext->ratingPositive = $dataRow['ratingpos'];
        $userContext->rating = $dataRow['rating'];
        $userContext->totalNumberOfReplies = $dataRow['replies'];
        $userContext->active = $dataRow['active'];

        $preferences = new UserContextPreferences();
        $preferences->afterReply = $dataRow['afterreply'];
        $preferences->autoStartTimeWorked = $dataRow['autostart'];
        $preferences->autoreload = $dataRow['autoreload'];
        $preferences->defaultNotifyCustomerNewTicket = $dataRow['notify_customer_new'];
        $preferences->defaultNotifyCustomerReply = $dataRow['notify_customer_reply'];
        $preferences->showSuggestedKnowledgebaseArticles = $dataRow['show_suggested'];
        $preferences->defaultCalendarView = $dataRow['default_calendar_view'];
        $preferences->defaultTicketView = $dataRow['default_list'];
        $userContext->preferences = $preferences;

        $notifications = new UserContextNotifications();
        $notifications->newUnassigned = $dataRow['notify_new_unassigned'];
        $notifications->newAssignedToMe = $dataRow['notify_new_my'];
        $notifications->replyUnassigned = $dataRow['notify_reply_unassigned'];
        $notifications->replyToMe = $dataRow['notify_reply_my'];
        $notifications->ticketAssignedToMe = $dataRow['notify_assigned'];
        $notifications->privateMessage = $dataRow['notify_pm'];
        $notifications->noteOnTicketAssignedToMe = $dataRow['notify_note'];
        $notifications->noteOnTicketNotAssignedToMe = $dataRow['notify_note_unassigned'];
        $notifications->overdueTicketUnassigned = $dataRow['notify_overdue_unassigned'];
        $userContext->notificationSettings = $notifications;

        return $userContext;
    }
}