<?php

namespace App\Command;

use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\CoreBundle\Framework\FrameworkAwareInterface;
use Contao\CoreBundle\Framework\FrameworkAwareTrait;
use Contao\CalendarEventsModel;
use Contao\StringUtil;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class FillCalendarCommand extends Command implements FrameworkAwareInterface
{
    use FrameworkAwareTrait;
    protected static string $defaultName = 'app:fill-calendar';

    // Konstanten für die tl_calendar_events Einträge
    private const int AUTHOR_ID = 1;

    private HttpClientInterface $httpClient;

    private string $verband;

    private bool $dryRun;


    public function __construct(ContaoFramework $framework)
    {
        parent::__construct();
        $this->framework = $framework;
        $this->framework->initialize();
    }

    protected function configure(): void
    {
        $this->setName($this::$defaultName)
        ->setDescription('Fill calendar from remote Sites data')
        ->addOption('dry-run', null, InputOption::VALUE_NONE, 'Testlauf (Datenbank nicht ändern)')
        ->addArgument('verband', InputArgument::REQUIRED, 'Name des Verbands (aktuell werden BeDV und SDM unterstützt)')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<info>Fill calendar</info>');

        $this->dryRun = $input->getOption('dry-run');
        $this->verband = $input->getArgument('verband');

        $output->writeln('dry-run: '.($this->dryRun ? 'ja' : 'nein'));
        $output->writeln('verband: '.$this->verband);

        $this->httpClient = HttpClient::create(
            [
                'base_uri' => $this->getUrlForVerband()
            ]
        );

        try {
            foreach ($this->getConfigForVerband() as $ligaId => $mannschaften) {
                foreach ($mannschaften as $mannschaftId => $calendarId) {
                    $this->getAndStoreData($output, $mannschaftId, $ligaId, $calendarId);
                }
            }
        } catch (Exception $e) {
            print_r($e);
        }

        return Command::SUCCESS;
    }

    /**
     * @throws Exception
     */
    protected function getUrlForVerband(): string
    {
        return match ($this->verband) {
            "SDM" => 'https://steeldart-muenchen.de',
            "BeDV" => 'https://www.edart-bayern.de',
            default => throw new Exception("Unbekannter Verband '{$this->verband}'"),
        };
    }

    /**
     * Datenstruktur:
     * [
     *     '<Verband>' => [
     *             <Liga-ID> => [
     *                 <Mannschaft-ID> => <Kalender-ID>
     *         ]
     *     ]
     * ]
     * TODO:
     *     - in externe *.yaml auslagern und deren Name als Parameter übergeben
     */
    protected function getConfigForVerband(): array
    {
        $config = [
            'SDM' => [ /* calendar-ID 5 === 'SDM Ligaspiele' */
                14 => [ // 4er - 1. Liga 2026/2027
                    224 => 5, // Zack Schnellbutton yo
                ],
                16 => [ // 4er - 2. Liga 2026/2027
                    227 => 5, // Harlekin
                ],
                17 => [ // 4er - 3. Liga 2026/2027
                    238 => 5, // Steel-Ikonen
                ],
                18 => [ // 4er - 4. Liga 2026/2027
                    249 => 5, // Darts and Roses
                    245 => 5, // Söldners Södertruppe
                    252 => 5, // Team Uwe
                ],
                // 19 => [ // 4er - 5. Liga 2026/2027
                //     // Keine Mannschaft aus dem Harlekin
                // ],

            ],

            'BeDV' => [ /* calendar-ID 4 === 'BEDV Ligaspiele' */
                238 => [ // Bezirksliga 2026/27
                  1183  => 4,
                ],
                // 236 => [ // "A1 2026/2027"
                //     1127 => 4,
                //     1181 => 4,
                // ],
                // // 232 => [ // "B1 2026/2027"
                // // ],
                // // 234 => [ // "B2 2026/2027"
                // // ],
                // 225 => [ // "C1 2026/2027"
                //     1188 => 4,
                //     1192 => 4,
                // ],
                // 229 => [ // "C2 2026/2027"
                //     1200 => 4,
                //     1191 => 4,
                // ],
                // 230 => [ // "C3 2026/2027"
                //     1146 => 4,
                //     1169 => 4,
                // ],
                //
                // 230 => [ // "MeD 2026/2027"
                //     1230 => 20,
                //     1234 => 20,
                //     1222 => 20,
                // ],

            ]
        ];


        return $config[$this->verband] ?? [];

    }

    /**
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    protected function getAndStoreData(OutputInterface $output, int $mannschaftId, int $ligaId, int $calendarId): void
    {
        $output->writeln(sprintf('<info>Mannschaft %s, Liga %s, Kalender %s</info>', $mannschaftId, $ligaId, $calendarId));

        try {
            $request = $this->httpClient->request('GET', sprintf('ligaverwaltung/spielplan/json/%d/%d', $ligaId, $mannschaftId));

            $data = $request->toArray();
            // dd($data);

            foreach ($data as $event) {
                if (!$event['is_home_game']) {
                    continue; // nur Heimspiele eintragen
                }

                $model = new CalendarEventsModel();
                $model->tstamp    = time();
                $model->pid       = $calendarId;
                $model->author    = self::AUTHOR_ID;
                // $model->title     = sprintf("%s %s: %s", $this->verband, $event['liga'], $event['title']);
                $model->title     = sprintf("%s: %s", $this->verband, $event['title']);
                $model->location  = sprintf("%s, %s", $event['location']['name'], $event['location']['address']);
                $model->startDate = $event['startDateTime'];
                $model->addTime   = true;
                $model->startTime = $event['startDateTime'];
                $model->endTime   = $event['startDateTime'];
                $model->spielort  = 'home'; // vs. 'away' !$event['is_home_game']
                // SDM-Termine haben alle 00:00 Uhr als Zeitangabe (siehe Hook in der dortigen Konfiguration!)
                $localTime = \DateTime::createFromTimestamp($event['startDateTime'])
                    ->setTimezone(new \DateTimeZone('Europe/Berlin'))
                    ->format('H:i');
                if ($localTime === '00:00') {
                    $model->startTime = $event['startDateTime'] + 20*60*60; // 0:00 Uhr + 20 Stunden * 60 Minuten * 60 Sekunden
                    $model->endTime   = $event['startDateTime'] + 20*60*60; // identisch zu Startzeit
                }
                $model->source    = 'default'; /* source ist das "Weiterleitungsziel" */
                $model->published = true;

                $aliasCandidate   = StringUtil::generateAlias($model->title);

                $duplicate = CalendarEventsModel::findByAlias($aliasCandidate);
                if ($duplicate) {
                    $output->writeln(sprintf('<error>Event %s gibt es bereits. Bitte manuell prüfen und ggf. korrigieren</error>', $aliasCandidate));
                    // // $duplicate löschen und mit potenziell neuen Daten neu anlegen
                    // // Beachte: es könnte Ligen geben, bei denen zwei Mannschaften pro Saison mehrmals (am gleichen Spielort) gegeneinander spielen
                    // // TODO dafür in der Ligaverwaltung: "spiel_tag" mit in den JSON Response aufnehmen und dann hier im alias berücksichtigen
                    // if (!$this->dryRun) {
                    //     $duplicate->delete();
                    // }
                } else {
                    $model->alias = $aliasCandidate;
                    if (!$this->dryRun) {
                        $model->save();
                        $output->writeln(sprintf('<info>gespeichert %s</info>', $model->title));
                    } else {
                        $output->writeln(sprintf('<info>Daten: %s</info>', json_encode($model->row())));
                    }
                }
            }

        } catch (Exception $e) {
            print_r($e);
        }

    }

}