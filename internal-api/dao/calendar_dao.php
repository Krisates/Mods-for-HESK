<?php

function get_events($start, $end, $hesk_settings, $staff = true) {
    global $hesk_settings, $hesklang;

    $start_time_sql = "CONVERT_TZ(FROM_UNIXTIME(" . hesk_dbEscape($start) . " / 1000), @@session.time_zone, '+00:00')";
    $end_time_sql = "CONVERT_TZ(FROM_UNIXTIME(" . hesk_dbEscape($end) . " / 1000), @@session.time_zone, '+00:00')";

    $sql = "SELECT `events`.*, `categories`.`name` AS `category_name`, `categories`.`background_color` AS `background_color`, 
        `categories`.`foreground_color` AS `foreground_color`, `categories`.`display_border_outline` AS `display_border` ";

    if ($staff) {
        $sql .= ",`reminders`.`amount` AS `reminder_value`, `reminders`.`unit` AS `reminder_unit` ";
    }

    $sql .= "FROM `" . hesk_dbEscape($hesk_settings['db_pfix']) . "calendar_event` AS `events`
        INNER JOIN `" . hesk_dbEscape($hesk_settings['db_pfix']) . "categories` AS `categories`
            ON `events`.`category` = `categories`.`id` ";

    if ($staff) {
        $sql .= "LEFT JOIN `" . hesk_dbEscape($hesk_settings['db_pfix']) . "calendar_event_reminder` AS `reminders` ON
        `reminders`.`user_id` = " . intval($_SESSION['id']) . " AND `reminders`.`event_id` = `events`.`id`";
    }
    $sql .= "WHERE NOT (`end` < {$start_time_sql} OR `start` > {$end_time_sql}) AND `categories`.`usage` <> 1";

    if (!$staff) {
        $sql .= " AND `categories`.`type` = '0'";
    }

    $rs = hesk_dbQuery($sql);

    $events = array();
    while ($row = hesk_dbFetchAssoc($rs)) {
        // Skip the event if the user does not have access to it
        if ($staff && !$_SESSION['isadmin'] && !in_array($row['category'], $_SESSION['categories'])) {
            continue;
        }

        mfh_log_debug('Calendar', "Creating event with id: {$row['id']}", '');

        $event['type'] = 'CALENDAR';
        $event['id'] = intval($row['id']);
        $event['startTime'] = $row['start'];
        $event['endTime'] = $row['end'];
        $event['allDay'] = $row['all_day'] ? true : false;
        $event['title'] = $row['name'];
        $event['location'] = $row['location'];
        $event['comments'] = $row['comments'];
        $event['categoryId'] = $row['category'];
        $event['categoryName'] = $row['category_name'];
        $event['backgroundColor'] = $row['background_color'];
        $event['foregroundColor'] = $row['foreground_color'];
        $event['displayBorder'] = $row['display_border'];

        if ($staff) {
            $event['reminderValue'] = $row['reminder_value'];
            $event['reminderUnits'] = $row['reminder_unit'];
        }

        $events[] = $event;
    }

    if ($staff) {
        $old_time_setting = $hesk_settings['timeformat'];
        $hesk_settings['timeformat'] = 'Y-m-d';
        $current_date = hesk_date();
        $hesk_settings['timeformat'] = $old_time_setting;

        $sql = "SELECT `trackid`, `subject`, `due_date`, `category`, `categories`.`name` AS `category_name`, `categories`.`background_color` AS `background_color`, 
        `categories`.`foreground_color` AS `foreground_color`, `categories`.`display_border_outline` AS `display_border`,
          CASE WHEN `due_date` < '{$current_date}' THEN 1 ELSE 0 END AS `overdue`, `owner`.`name` AS `owner_name`, `tickets`.`owner` AS `owner_id`,
           `tickets`.`priority` AS `priority`
        FROM `" . hesk_dbEscape($hesk_settings['db_pfix']) . "tickets` AS `tickets`
        INNER JOIN `" . hesk_dbEscape($hesk_settings['db_pfix']) . "categories` AS `categories`
            ON `categories`.`id` = `tickets`.`category`
            AND `categories`.`usage` <> 2
        LEFT JOIN `" . hesk_dbEscape($hesk_settings['db_pfix']) . "users` AS `owner`
            ON `tickets`.`owner` = `owner`.`id`
        WHERE `due_date` >= CONVERT_TZ(FROM_UNIXTIME(" . hesk_dbEscape($start)
        . " / 1000), @@session.time_zone, '+00:00')
        AND `due_date` <= CONVERT_TZ(FROM_UNIXTIME(" . hesk_dbEscape($end) . " / 1000), @@session.time_zone, '+00:00')
        AND `status` IN (SELECT `id` FROM `" . hesk_dbEscape($hesk_settings['db_pfix']) . "statuses` WHERE `IsClosed` = 0) ";

        $rs = hesk_dbQuery($sql);
        while ($row = hesk_dbFetchAssoc($rs)) {
            // Skip the ticket if the user does not have access to it
            if (!hesk_checkPermission('can_view_tickets', 0)
                || ($row['owner_id'] && $row['owner_id'] != $_SESSION['id'] && !hesk_checkPermission('can_view_ass_others', 0))
                || (!$row['owner_id'] && !hesk_checkPermission('can_view_unassigned', 0))) {
                continue;
            }

            $event['type'] = 'TICKET';
            $event['trackingId'] = $row['trackid'];
            $event['subject'] = $row['subject'];
            $event['title'] = $row['subject'];
            $event['startTime'] = $row['due_date'];
            $event['url'] = $hesk_settings['hesk_url'] . '/' . $hesk_settings['admin_dir'] . '/admin_ticket.php?track=' . $event['trackingId'];
            $event['categoryId'] = $row['category'];
            $event['categoryName'] = $row['category_name'];
            $event['backgroundColor'] = $row['background_color'];
            $event['foregroundColor'] = $row['foreground_color'];
            $event['displayBorder'] = $row['display_border'];
            $event['owner'] = $row['owner_name'];

            $priorities = array(
                0 => $hesklang['critical'],
                1 => $hesklang['high'],
                2 => $hesklang['medium'],
                3 => $hesklang['low']
            );
            $event['priority'] = $priorities[$row['priority']];

            $events[] = $event;
        }
    }

    return $events;
}

function create_event($event, $hesk_settings) {
    // Make sure the user can create events in this category
    if (!$_SESSION['isadmin'] && !in_array($event['category'], $_SESSION['categories'])) {
        print_error('Access Denied', 'You cannot create an event in this category');
    }

    $event['start'] = date('Y-m-d H:i:s', strtotime($event['start']));
    $event['end'] = date('Y-m-d H:i:s', strtotime($event['end']));
    $event['all_day'] = $event['all_day'] ? 1 : 0;

    $sql = "INSERT INTO `" . hesk_dbEscape($hesk_settings['db_pfix']) . "calendar_event` (`start`, `end`, `all_day`,
    `name`, `location`, `comments`, `category`) VALUES (
    '" . hesk_dbEscape($event['start']) . "', '" . hesk_dbEscape($event['end']) . "', '" . hesk_dbEscape($event['all_day']) . "',
    '" . hesk_dbEscape(addslashes($event['title'])) . "', '" . hesk_dbEscape(addslashes($event['location'])) . "', '" . hesk_dbEscape(addslashes($event['comments'])) . "',
    " . intval($event['category']) . ")";

    hesk_dbQuery($sql);
    $event_id = hesk_dbInsertID();

    if ($event['reminder_amount'] != null) {
        $sql = "INSERT INTO `" . hesk_dbEscape($hesk_settings['db_pfix']) . "calendar_event_reminder` (`user_id`, `event_id`,
        `amount`, `unit`) VALUES (" . intval($event['reminder_user']) . ", " . intval($event_id) . ", " . intval($event['reminder_amount']) . ",
        " . intval($event['reminder_units']) . ")";

        hesk_dbQuery($sql);
    }

    return $event_id;
}

function update_event($event, $hesk_settings) {
    // Make sure the user can edit events in this category
    if (!$_SESSION['isadmin'] && !in_array($event['category'], $_SESSION['categories'])) {
        print_error('Access Denied', 'You cannot edit an event in this category');
    }


}

function delete_event($id, $hesk_settings) {
    // Make sure the user can delete events in this category
    $categoryRs = hesk_dbQuery('SELECT `category` FROM `' . hesk_dbEscape($hesk_settings['db_pfix']) . 'calendar_event` WHERE `id` = ' . intval($id));
    $category = hesk_dbFetchAssoc($categoryRs);
    if (!$_SESSION['isadmin'] && !in_array($category['category'], $_SESSION['categories'])) {
        print_error('Access Denied', 'You cannot delete events in this category');
    }

    $sql = "DELETE FROM `" . hesk_dbEscape($hesk_settings['db_pfix']) . "calendar_event` WHERE `id` = " . intval($id);

    hesk_dbQuery($sql);
}

function update_ticket_due_date($ticket, $hesk_settings) {
    $ticket_id_rs = hesk_dbQuery("SELECT `id` FROM `" . hesk_dbEscape($hesk_settings['db_pfix']) . "tickets` WHERE `trackid` = '" . hesk_dbEscape($ticket['trackid']) . "'");
    $ticket_id = hesk_dbFetchAssoc($ticket_id_rs);

    $due_date = 'NULL';
    $language_key = 'audit_due_date_removed';
    $audit_array = array(0 => $_SESSION['name'] . ' (' . $_SESSION['user'] . ')');
    if ($ticket['due_date'] != NULL) {
        $audit_array = array(
            0 => $_SESSION['name'] . ' (' . $_SESSION['user'] . ')',
            1 => date('Y-m-d H:i:s', strtotime($ticket['due_date']))
        );
        $due_date = "'" . date('Y-m-d H:i:s', strtotime($ticket['due_date'])) . "'";
        $language_key = 'audit_due_date_changed';
    }
    $sql = "UPDATE `" . hesk_dbEscape($hesk_settings['db_pfix']) . "tickets` SET `due_date` = {$due_date}, `overdue_email_sent` = '0'
        WHERE `trackid` = '" . hesk_dbEscape($ticket['trackid']) . "'";

    mfh_insert_audit_trail_record($ticket_id['id'], 'TICKET', $language_key, hesk_date(),
        $audit_array);

    hesk_dbQuery($sql);
}