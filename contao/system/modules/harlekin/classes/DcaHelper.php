<?php
/**
 * Helper functions for Contao DCA.
 *
 * fiedsch@ja-eh.at
 */

namespace Harlekin;

use Contao\DataContainer;

class DcaHelper
{

    public function generateMonthOfBirth($varValue, DataContainer $dc)
    {
        $month = \Contao\Date::parse("m", $varValue);
        $dbResult = \Database::getInstance()
            ->prepare("UPDATE tl_member SET monthOfBirth =? WHERE id=?")
            ->execute($month, $dc->id);

        return $varValue;
    }

}