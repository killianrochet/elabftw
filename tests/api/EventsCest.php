<?php declare(strict_types=1);
/**
 * @package   Elabftw\Elabftw
 * @author    Nicolas CARPi <nico-git@deltablot.email>
 * @copyright 2022 Nicolas CARPi
 * @license   https://www.gnu.org/licenses/agpl-3.0.html AGPL-3.0
 * @see       https://www.elabftw.net Official website
 */

use \Codeception\Util\HttpCode;

class EventsCest
{
    public function _before(ApiTester $I)
    {
        $I->haveHttpHeader('Authorization', 'apiKey4Test');
    }

    public function createAndDeleteEventTest(ApiTester $I)
    {
        $I->wantTo('Create an event');
        $I->sendPOST('/events/2', array(
            'start' => '2022-03-05T12:00:00+01:00',
            'end' => '2022-03-05T14:00:00+01:00',
            'title' => 'Booked from API',
        ));
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $id = $I->grabDataFromResponseByJsonPath('$.id');
        $I->sendGET('/events/' . $id[0]);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(array(
            'start' => '2022-03-05T12:00:00+01:00',
            'end' => '2022-03-05T14:00:00+01:00',
            'title' => 'Booked from API',
        ));
        $I->wantTo('Delete an event');
        $I->sendDELETE('/events/' . $id[0]);
    }

    public function createEventWithNoIdTest(ApiTester $I)
    {
        $I->wantTo('Create an event but I forgot the item id');
        $I->sendPOST('/events/', array());
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
    }

    public function destroyEventWithNoIdTest(ApiTester $I)
    {
        $I->wantTo('Destroy an event but I forgot the event id');
        $I->sendPOST('/events/', array());
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
    }

    public function testGetBookableTest(ApiTester $I)
    {
        $I->sendGET('/bookable/');
        $I->seeResponseIsJson();
    }
}
