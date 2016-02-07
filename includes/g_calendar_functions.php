<?php



function g_cal_authenticate() {
	$user = 'webmaster@siskiyourappellers.com';
	$pass = 'siskiyou';
	$service = Zend_Gdata_Calendar::AUTH_SERVICE_NAME; // predefined service name for calendar

	$client = Zend_Gdata_ClientLogin::getHttpClient($user,$pass,$service);

	return $client;
}
/*=================================================================================================*/

function g_cal_createEvent ($client, $title = 'Untitled', $desc='', $where = '',
			    $startDate =	'2008-01-01',	$startTime = '0',
			    $endDate =	'2008-01-01',	$endTime = '0', $tzOffset = '-08')
{

  $gdataCal = new Zend_Gdata_Calendar($client);
  $newEvent = $gdataCal->newEventEntry();

  $newEvent->title = $gdataCal->newTitle($title);
  $newEvent->where = array($gdataCal->newWhere($where));
  $newEvent->content = $gdataCal->newContent("$desc");

 if($startTime == '0' || $startTime == '') { //Make this an All-Day Event
	$when = $gdataCal->newWhen();
	$when->startTime = "{$startDate}";
	$when->endTime = "{$endDate}";
 }
 else {
	$when = $gdataCal->newWhen();
	$when->startTime = "{$startDate}T{$startTime}:00.000{$tzOffset}:00";
	$when->endTime = "{$endDate}T{$endTime}:00.000{$tzOffset}:00";
}

  $newEvent->when = array($when);

  // Upload the event to the calendar server
  // A copy of the event as it is recorded on the server is returned
  $createdEvent = $gdataCal->insertEvent($newEvent);
/*
  //Store the new eventID in the cohelitack database
	//g_cal_updateEvent($client, $createdEvent->id, "New Title");
	echo "\$event->getLink('edit')->href: " . $createdEvent->getLink('edit')->href . "<br>\n";
	echo "\$event->getId(): " . $createdEvent->getId() . "<br>\n";
	
	$myEvent = getEvent($client,$createdEvent->getId());
	echo "\$myEvent->getId(): " . $myEvent->getId() . "<br>\n";
	
	//g_cal_deleteEventByUrl($client,$createdEvent->getLink('edit')->href);
	//g_cal_deleteEventById($client,$createdEvent->getId());
*/
  return $createdEvent->getLink('edit')->href;
}
/*=================================================================================================*/

function g_cal_updateEvent ($client, $eventEditUrl,
							$title = '', $desc='', $where = '', $startDate = '', $endDate = '')
{

    $gdataCal = new Zend_Gdata_Calendar($client);

    if ($eventOld = $gdataCal->getEvent($eventId)) {
      echo "Old title: " . $eventOld->title->text . "<br />\n";
      $eventOld->title = $gdataCal->newTitle($newTitle);

      try {
        $eventOld->save();
      } catch (Zend_Gdata_App_Exception $e) {

        var_dump($e);
        return null;
      }

      $eventNew = getEvent($client, $eventId);
      echo "New title: " . $eventNew->title->text . "<br />\n";

      return $eventNew;
    } else {
      return null;
    }

}

/*=================================================================================================*/
function getEvent($client, $eventId)
{
  $gdataCal = new Zend_Gdata_Calendar($client);
  $query = $gdataCal->newEventQuery();
  $query->setUser('default');
  $query->setVisibility('private');
  $query->setProjection('full');
  $query->setEvent($eventId);

  try {
    $eventEntry = $gdataCal->getCalendarEventEntry($query);
    return $eventEntry;
  } catch (Zend_Gdata_App_Exception $e) {
    var_dump($e);
    return false;
  }
} 
/*=================================================================================================*/
function getEventByUrl($eventEditUrl)
{
  $gdataCal = new Zend_Gdata_Calendar($client);
  $query = $gdataCal->newEventQuery();
  $query->setUser('default');
  $query->setVisibility('private');
  $query->setProjection('full');
  $query->setEvent($eventId);

  try {
    $eventEntry = $gdataCal->getCalendarEventEntry($query);
    return $eventEntry;
  } catch (Zend_Gdata_App_Exception $e) {
    var_dump($e);
    return false;
  }
} 
/*=================================================================================================*/
function g_cal_deleteEventByUrl($client, $eventEditUrl) {
	$gdataCal = new Zend_Gdata_Calendar($client);
	$gdataCal->delete($eventEditUrl);
}
/*=================================================================================================*/
function g_cal_deleteEventById($client, $eventId) {
	$gdataCal = new Zend_Gdata_Calendar($client);
	$event = $gdataCal->getEvent($eventId);
	
	$event->delete();
	
}
?>