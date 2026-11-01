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
    SET addTime = ?,
        published = ?
    WHERE id = ?
SQL;

       $this->connection->executeQuery($query, [true, true, $insertId]);

    }

    public function handleFieldsDisplay(DataContainer $dc): void
    {
        // Für die App nicht relevante Felder nicht zum Filtern anbieten
        $GLOBALS['TL_DCA']['tl_calendar_events']['fields']['featured']['filter']   = false;
        $GLOBALS['TL_DCA']['tl_calendar_events']['fields']['recurring']['filter']   = false;
        $GLOBALS['TL_DCA']['tl_calendar_events']['fields']['searchIndexer']['filter'] = false;

        // Nur bei allgemeinen Kalendern (keine Ligaspiele)
        if (!in_array($dc->currentPid, $this->getSpielplanCalendarIds())) {
            PaletteManipulator::create()
                ->addField('startDate', 'date_legend', PaletteManipulator::POSITION_PREPEND)
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
            ->addLegend('date_legend', 'title_legend', PaletteManipulator::POSITION_AFTER)
            //->addField('startDate', 'date_legend', PaletteManipulator::POSITION_PREPEND)
            //->addField('endDate', 'startDate', PaletteManipulator::POSITION_AFTER)
            //->addField('startTime', 'endDate', PaletteManipulator::POSITION_AFTER)
            ->addField('startDate', 'date_legend', PaletteManipulator::POSITION_PREPEND)
            ->addField('startTime', 'startDate', PaletteManipulator::POSITION_AFTER)
            ->addField('endDate', 'startTime', PaletteManipulator::POSITION_AFTER)
            ->addField('endTime', 'endDate', PaletteManipulator::POSITION_AFTER)
            ->applyToPalette('default', 'tl_calendar_events')
        ;

        PaletteManipulator::create()
            ->addField('spielort', 'endTime',PaletteManipulator::POSITION_AFTER)
            ->applyToPalette('default', 'tl_calendar_events')
        ;

        $GLOBALS['TL_DCA']['tl_calendar_events']['fields']['startDate']['eval']['tl_class'] = 'w25';
        $GLOBALS['TL_DCA']['tl_calendar_events']['fields']['endDate']['eval']['tl_class'] = 'w25';
        $GLOBALS['TL_DCA']['tl_calendar_events']['fields']['startTime']['eval']['tl_class'] = 'w25';
        $GLOBALS['TL_DCA']['tl_calendar_events']['fields']['endTime']['eval']['tl_class']   = 'w25';

        $GLOBALS['TL_DCA']['tl_calendar_events']['fields']['title']['eval']['tl_class']   = 'long';
    }

    public function handleFieldsLabels(DataContainer $dc): void
    {
        // Nur bei Kalendern für Ligaspiele
        if (!in_array($dc->currentPid, $this->getSpielplanCalendarIds())) { return; }

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
            'Spiel',
            'Verband: Heimmannschaft vs. Gastmannschaft.'
        ];

        $GLOBALS['TL_LANG']['tl_calendar_events']['spielort'] = [
            'Spielort',
            'Ort an dem dieses Ligaspiel stattfindet. Wird zum Filtern verwendet.'
        ];

    }

    public function handleCalendarOperations(DataContainer $dc): void
    {
        if (!in_array($dc->currentPid, $this->getSpielplanCalendarIds())) { return; }
        // we don't allow (a teaser or) children, so remove the respective edit icon
        // also: we dont care about featured events here
        unset($GLOBALS['TL_DCA']['tl_calendar_events']['list']['operations']['children']);
        unset($GLOBALS['TL_DCA']['tl_calendar_events']['list']['operations']['feature']);
    }

    protected function getSpielplanCalendarIds(): array
    {
        return array_keys($this->connection->executeQuery('SELECT id FROM tl_calendar where spielplan=1')->fetchAllAssociativeIndexed());
    }

}

