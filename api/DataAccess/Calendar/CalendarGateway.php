<?php

namespace DataAccess\Calendar;


use BusinessLogic\Calendar\AbstractEvent;
use BusinessLogic\Calendar\CalendarEvent;
use BusinessLogic\Calendar\ReminderUnit;
use BusinessLogic\Calendar\SearchEventsFilter;
use BusinessLogic\Calendar\TicketEvent;
use BusinessLogic\Helpers;
use BusinessLogic\Security\UserContext;
use Core\Constants\Priority;
use DataAccess\CommonDao;
use DataAccess\Logging\LoggingGateway;

class CalendarGateway extends CommonDao {
    /**
     * @param $searchEventsFilter SearchEventsFilter
     * @param $heskSettings array
     * @return AbstractEvent[]
     */
    public function getEventsForStaff($searchEventsFilter, $heskSettings) {
        $this->init();

        $events = array();

        // EVENTS
        $sql = "SELECT `events`.*, `categories`.`name` AS `category_name`, `categories`.`background_color` AS `background_color`,
                    `categories`.`foreground_color` AS `foreground_color`, `categories`.`display_border_outline` AS `display_border`,
                    `reminders`.`amount` AS `reminder_value`, `reminders`.`unit` AS `reminder_unit`
                FROM `" . hesk_dbEscape($heskSettings['db_pfix']) . "calendar_event` AS `events`
                INNER JOIN `" . hesk_dbEscape($heskSettings['db_pfix']) . "categories` AS `categories`
                    ON `events`.`category` = `categories`.`id`
                LEFT JOIN `" . hesk_dbEscape($heskSettings['db_pfix']) . "calendar_event_reminder` AS `reminders`
                    ON `reminders`.`user_id` = " . intval($searchEventsFilter->reminderUserId) . "
                    AND `reminders`.`event_id` = `events`.`id`
                WHERE 1=1";

        if ($searchEventsFilter->startTime !== null && $searchEventsFilter->endTime !== null) {
            $startTimeSql = "CONVERT_TZ(FROM_UNIXTIME(" . hesk_dbEscape($searchEventsFilter->startTime) . " / 1000), @@session.time_zone, '+00:00')";
            $endTimeSql = "CONVERT_TZ(FROM_UNIXTIME(" . hesk_dbEscape($searchEventsFilter->endTime) . " / 1000), @@session.time_zone, '+00:00')";


            $sql .= " AND NOT (`end` < {$startTimeSql} OR `start` > {$endTimeSql})
                    AND `categories`.`usage` <> 1
                    AND `categories`.`type` = '0'";
        }

        if ($searchEventsFilter->eventId !== null) {
            $sql .= " AND `events`.`id` = " . intval($searchEventsFilter->eventId);
        }

        if (!empty($searchEventsFilter->categories)) {
            $categoriesAsString = implode(',', $searchEventsFilter->categories);
            $sql .= " AND `events`.`category` IN (" . $categoriesAsString . ")";
        }

        $rs = hesk_dbQuery($sql);
        while ($row = hesk_dbFetchAssoc($rs)) {
            $event = new CalendarEvent();
            $event->id = intval($row['id']);
            $event->startTime = $row['start'];
            $event->endTime = $row['end'];
            $event->allDay = Helpers::boolval($row['all_day']);
            $event->title = $row['name'];
            $event->location = $row['location'];
            $event->comments = $row['comments'];
            $event->categoryId = intval($row['category']);
            $event->categoryName = Helpers::heskHtmlSpecialCharsDecode($row['category_name']);
            $event->backgroundColor = $row['background_color'];
            $event->foregroundColor = $row['foreground_color'];
            $event->displayBorder = Helpers::boolval($row['display_border']);
            $event->reminderValue = $row['reminder_value'] === null ? null : floatval($row['reminder_value']);
            $event->reminderUnits = $row['reminder_unit'] === null ? null : ReminderUnit::getByValue($row['reminder_unit']);

            $events[] = $event;
        }

        // TICKETS
        if ($searchEventsFilter->includeTickets) {
            $oldTimeSetting = $heskSettings['timeformat'];
            $heskSettings['timeformat'] = 'Y-m-d';
            $currentDate = hesk_date();
            $heskSettings['timeformat'] = $oldTimeSetting;

            $sql = "SELECT `tickets`.`id` AS `id`, `trackid`, `subject`, `due_date`, `category`, `categories`.`name` AS `category_name`, `categories`.`background_color` AS `background_color`, 
                `categories`.`foreground_color` AS `foreground_color`, `categories`.`display_border_outline` AS `display_border`,
                  CASE WHEN `due_date` < '{$currentDate}' THEN 1 ELSE 0 END AS `overdue`, `owner`.`name` AS `owner_name`, `tickets`.`owner` AS `owner_id`,
                   `tickets`.`priority` AS `priority`
                FROM `" . hesk_dbEscape($heskSettings['db_pfix']) . "tickets` AS `tickets`
                INNER JOIN `" . hesk_dbEscape($heskSettings['db_pfix']) . "categories` AS `categories`
                    ON `categories`.`id` = `tickets`.`category`
                    AND `categories`.`usage` <> 2
                LEFT JOIN `" . hesk_dbEscape($heskSettings['db_pfix']) . "users` AS `owner`
                    ON `tickets`.`owner` = `owner`.`id`
                WHERE `due_date` >= {$startTimeSql}
                AND `due_date` <= {$endTimeSql}
                AND `status` IN (SELECT `id` FROM `" . hesk_dbEscape($heskSettings['db_pfix']) . "statuses` WHERE `IsClosed` = 0) 
                AND (`owner` = " . $searchEventsFilter->reminderUserId;

            if ($searchEventsFilter->includeUnassignedTickets) {
                $sql .= " OR `owner` = 0 ";
            }

            if ($searchEventsFilter->includeTicketsAssignedToOthers) {
                $sql .= " OR `owner` NOT IN (0, " . $searchEventsFilter->reminderUserId . ") ";
            }

            $sql .= ")";

            if (!empty($searchEventsFilter->categories)) {
                $categoriesAsString = implode(',', $searchEventsFilter->categories);
                $sql .= " AND `tickets`.`category` IN (" . $categoriesAsString . ")";
            }

            $rs = hesk_dbQuery($sql);
            while ($row = hesk_dbFetchAssoc($rs)) {
                $event = new TicketEvent();
                $event->id = intval($row['id']);
                $event->trackingId = $row['trackid'];
                $event->subject = $row['subject'];
                $event->title = $row['subject'];
                $event->startTime = $row['due_date'];
                $event->url = $heskSettings['hesk_url'] . '/' . $heskSettings['admin_dir'] . '/admin_ticket.php?track=' . $event->trackingId;
                $event->categoryId = intval($row['category']);
                $event->categoryName = Helpers::heskHtmlSpecialCharsDecode($row['category_name']);
                $event->backgroundColor = $row['background_color'];
                $event->foregroundColor = $row['foreground_color'];
                $event->displayBorder = Helpers::boolval($row['display_border']);
                $event->owner = $row['owner_name'];
                $event->priority = Priority::getByValue($row['priority']);

                $events[] = $event;
            }
        }

        $this->close();

        return $events;
    }

    /**
     * @param $event CalendarEvent
     * @param $userContext UserContext
     * @param $heskSettings array
     * @return CalendarEvent
     */
    public function createEvent($event, $userContext, $heskSettings) {
        $this->init();

        hesk_dbQuery("INSERT INTO `" . hesk_dbEscape($heskSettings['db_pfix']) . "calendar_event` (`start`, `end`, `all_day`, `name`,
            `location`, `comments`, `category`) VALUES ('" . hesk_dbEscape($event->startTime) . "', '" . hesk_dbEscape($event->endTime) . "',
                '" . ($event->allDay ? 1 : 0) . "', '" . hesk_dbEscape(addslashes($event->title)) . "',
                '" . hesk_dbEscape(addslashes($event->location)) . "', '". hesk_dbEscape(addslashes($event->comments)) . "', " . intval($event->categoryId) . ")");

        $event->id = hesk_dbInsertID();

        if ($event->reminderValue !== null) {
            hesk_dbQuery("INSERT INTO `" . hesk_dbEscape($heskSettings['db_pfix']) . "calendar_event_reminder` (`user_id`, `event_id`,
                `amount`, `unit`) VALUES (" . intval($userContext->id) . ", " . intval($event->id) . ", " . intval($event->reminderValue) . ",
                " . intval($event->reminderUnits) . ")");
        }

        $this->close();

        return $event;
    }

    /**
     * @param $event CalendarEvent
     * @param $userContext UserContext
     * @param $heskSettings array
     */
    public function updateEvent($event, $userContext, $heskSettings) {
        $this->init();

        $sql = "UPDATE `" . hesk_dbEscape($heskSettings['db_pfix']) . "calendar_event` SET `start` = '" . hesk_dbEscape($event->startTime)
            . "', `end` = '" . hesk_dbEscape($event->endTime) . "', `all_day` = '" . ($event->allDay ? 1 : 0) . "', `name` = '"
            . hesk_dbEscape(addslashes($event->title)) . "', `location` = '" . hesk_dbEscape(addslashes($event->location)) . "', `comments` = '"
            . hesk_dbEscape(addslashes($event->comments)) . "', `category` = " . intval($event->categoryId) . " WHERE `id` = " . intval($event->id);

        if ($event->reminderValue !== null) {
            $delete_sql = "DELETE FROM `" . hesk_dbEscape($heskSettings['db_pfix']) . "calendar_event_reminder` WHERE `event_id` = " . intval($event->id)
                . " AND `user_id` = " . intval($userContext->id);
            hesk_dbQuery($delete_sql);
            $insert_sql = "INSERT INTO `" . hesk_dbEscape($heskSettings['db_pfix']) . "calendar_event_reminder` (`user_id`, `event_id`,
        `amount`, `unit`) VALUES (" . intval($userContext->id) . ", " . intval($event->id) . ", " . intval($event->reminderValue) . ",
        " . intval($event->reminderUnits) . ")";
            hesk_dbQuery($insert_sql);
        }

        hesk_dbQuery($sql);

        $this->close();
    }
}