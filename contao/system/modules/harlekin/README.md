# DC-Harlekin München Homepage

## Anpassungen

* `langconfig.php` 

```php
$GLOBALS['TL_LANG']['MSC']['cal_emptyDay'] = "Heute kein Ligaspiel. Also auf geht's Leit: Training is o'gsagt!";
```

## TODOs

Das Feld (die Datenbankspalte) 

```php
$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['im_harlekin']` 
```

umbenennen damit es (generischer ist und) besser zu 

```php
$GLOBALS['TL_LANG']['tl_module']['cal_home_only']`
```
passt