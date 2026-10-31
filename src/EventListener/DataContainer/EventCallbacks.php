<?php

declare(strict_types = 1);

namespace App\EventListener\DataContainer;

use Contao\CoreBundle\DataContainer\PaletteManipulator;
use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\DataContainer;
use Doctrine\DBAL\Connection;


class EventCallbacks
{
    public function __construct(private Connection $connection)
    {
    }

    #[AsCallback(table: 'tl_calendar_events', target: 'config.onbeforesubmit')]
    public function onBeforeSubmit(
        array $record,
        DataContainer $dc
    )
    {
        $record['published'] = true;

        return $record;
    }

    #[AsCallback(table: 'tl_calendar_events', target: 'config.oncreate')]
    public function onCreate(
        string $table,
        int $insertId,
        array $record,
        DataContainer $dc
    )
    {
        $query = <<<SQL
UPDATE tl_calendar_events
    SET addTime = ?
    WHERE id = ?
SQL;

       $this->connection->executeQuery($query, [true,  $insertId]);

    }

//    #[AsCallback(table: 'tl_calendar_events', target: 'config.onload')]
//    public function onLoad(DataContainer $dc)
//    {
//        $currentRecord = $dc->getCurrentRecord();
//
//        if (empty($currentRecord['startTime'])) {
//            // TODO: set to a default time (instead of "now)
//        }
//    }

    public function handleFieldsDisplay(DataContainer $dc): void
    {
        // Für die App nicht relevante Felder nicht zum Filtern anbieten
        $GLOBALS['TL_DCA']['tl_calendar_events']['fields']['featured']['filter']   = false;
        $GLOBALS['TL_DCA']['tl_calendar_events']['fields']['recurring']['filter']   = false;
        $GLOBALS['TL_DCA']['tl_calendar_events']['fields']['searchIndexer']['filter'] = false;

        // Nur bei Kalendern für Ligaspiele
        if (!in_array($dc->currentPid, [4,7,20,6,5])) {
            PaletteManipulator::create()
                ->addField('endDate', 'startDate', PaletteManipulator::POSITION_AFTER)
                ->addField('spielort', 'title', PaletteManipulator::POSITION_AFTER) /* hier mit anderem Label @see self::handleFieldsLabels() */
                ->applyToPalette('default', 'tl_calendar_events')
            ;
            return;
        }
        PaletteManipulator::create()
            ->removeField('teaser')
            ->removeField('addImage')
            ->removeField('recurring')
            ->removeField('noComments')
            ->removeField('location')
            ->removeField('addTime') // does not only hide the field, but also the startTime and endTime fields (from the sub palette)
            ->applyToPalette('default', 'tl_calendar_events')
        ;

        PaletteManipulator::create()
            ->removeField('startTime')
            ->removeField('endTime')
            ->removeField('spielort')
            ->applyToSubPalette('addTime', 'tl_calendar_events')
        ;

        PaletteManipulator::create()
            ->addField('startTime', 'startDate', PaletteManipulator::POSITION_AFTER)
            ->applyToPalette('default', 'tl_calendar_events')
        ;

        PaletteManipulator::create()
            ->addField('endTime', 'startTime', PaletteManipulator::POSITION_AFTER)
            ->addField('spielort', 'endTime',PaletteManipulator::POSITION_AFTER)
            ->applyToPalette('default', 'tl_calendar_events')
        ;

        $GLOBALS['TL_DCA']['tl_calendar_events']['fields']['startDate']['eval']['tl_class'] = 'w33 clr';
        $GLOBALS['TL_DCA']['tl_calendar_events']['fields']['startTime']['eval']['tl_class'] = 'w33';
        $GLOBALS['TL_DCA']['tl_calendar_events']['fields']['endTime']['eval']['tl_class']   = 'w33';


    }

    public function handleFieldsLabels(DataContainer $dc): void
    {
        // Nur bei Kalendern für Ligaspiele
        if (!in_array($dc->currentPid, [4,7,20,6,5])) { return; }

        $GLOBALS['TL_LANG']['tl_calendar_events']['startDate'] = [
            'Datum',
            'Spieltag'
        ];

        $GLOBALS['TL_LANG']['tl_calendar_events']['startTime'] = [
            'Startzeit',
            'Geben Sie die Startzeit des Ligaspiels ein.'
        ];

        $GLOBALS['TL_LANG']['tl_calendar_events']['endTime'] = [
            'Endzeit',
            'Geben Sie die Endzeit des Ligaspiels ein. Leer lassen, wenn nicht bekannt.'
        ];

        $GLOBALS['TL_LANG']['tl_calendar_events']['title'] = [
            'Event Titel',
            'Typischerweise im Format: Liga: Heimmannschaft : Gastmannschaft.'
        ];

        $GLOBALS['TL_LANG']['tl_calendar_events']['spielort'] = [
            'Spielort',
            'Ort an dem dieses Ligaspiel stattfindet. Wird zum Filtern verwendet.'
        ];

    }

}

