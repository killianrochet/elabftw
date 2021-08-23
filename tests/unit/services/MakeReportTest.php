<?php declare(strict_types=1);
/**
 * @author Nicolas CARPi <nico-git@deltablot.email>
 * @copyright 2012 Nicolas CARPi
 * @see https://www.elabftw.net Official website
 * @license AGPL-3.0
 * @package elabftw
 */

namespace Elabftw\Services;

use Elabftw\Models\Teams;
use Elabftw\Models\Users;

class MakeReportTest extends \PHPUnit\Framework\TestCase
{
    private MakeReport $Make;

    protected function setUp(): void
    {
        $this->Make = new MakeReport(new Teams((new Users(1, 1))));
    }

    public function testGetFileName(): void
    {
        $this->assertEquals(Filter::kdate() . '-report.elabftw.csv', $this->Make->getFileName());
    }

    public function testGetCsv(): void
    {
        $this->assertIsString($this->Make->getCsv());
    }
}