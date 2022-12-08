# git

## Challenge
>Immagina di costruire un sistema di rilevazione presenze per il personale aziendale.
Ci sarà una pagina web dove sarà possibile inserire un codice.
Ogni utente ha un suo codice identificativo (es. ABC o DEF)
>
>Nel momento in cui si inserisce il codice e si invia, 
il sistema deve registrare la rilevazione della presenza e deve restituire un messaggio che dice 
“Benvenuto” se si tratta di un entrata, oppure “Arrivederci” se si tratta di un’uscita per lo specifico utente.
>
>Un database dovrà raccogliere tutte le rilevazioni effettuate, per avere traccia di tutti gli accessi e le uscite.
>
>Sentiti libero di impostare il tutto come preferisci, sia lato codice che a livello di struttura del Database.
>
>Un plus potrebbe essere la possibilità di scaricare un report,
tale per cui inserendo il codice utente, data di inizio e data di fine,
il sistema restituisce in download un foglio Excel o CSV con tutte le rilevazioni effettuate per quell’utente.
>

## Com'è abbordata la sfida
Si procede a disegnare l'app basata su una DB MySQL, in cui la tabella principale avrà registro di `id`, `utente`, `data_e_ora`, `status`. Il campo `utente` limitato a 8 caratteri  __CHAR(8)__ e il campo `data_e_ora` dal formato __DATETIME__. Il campo `satus` verrà utilizzato come un _boolean_ per indicare 'acceso' e 'uscita', però sarà dal tipo __INT(1)__, dando la possibilità a futuro di inserire altri tipi di status.

Tramite l'utilizzo di PHP per il backend, viene definita la classe `Utente` per gestire la connessione alla DB e i query.

Il codice HTML verrà creato per due file (index.php e admin.php) dal seguente modo:
+ Nel `index` sarà solo un formulario che permette di ingresare un codice utente. Se il codice è valido, se inserisce una nuova riga nella DB, contenendo il codice utente, data e ora, e se si trata di una entrata o uscita dallo stabilimento. Secondo lo status, verrà stampato un messaggio "Benvenuto" o "Arrivederci"
+ In caso che il codice utente sia sbagliato il messaggio invece dirà "Utente errato"
+ Il file `admin` permette di ottenere tutti i registri di acceso e uscite per uno specifico utente nel periodo richiesto. 
    + Quando si lascia un campo di data vuoto, internamente verrà impostato come la data attuale.
    + Lasciare il campo "Utente" vuoto (una stringa vuota) ritornerà i dati di tutti gli utenti.
    + In caso di che l'utente sia errato, il periodo selezionato sia sbagliato (es. impostare una data di inizio posteriore a quella di fine), oppure se nel periodo richiesto non ci sono registri per lo specifico utente, se stampa il messaggio "Senza dati"
+ Nel file `admin` se si effettua una richiesta valida, si mostra una tabella con i dati (aggruppati per utente), e verrà creato un pulsante per scaricare la informazione in formato CSV
+ Il processo di scarica verrà in automatico, tramite il file `csv.php` e sarà salvato con nome ___dati_scaricati.csv___ (separatore `;`)


## Istruzioni per l'istallazione

1.  Scaricare tutti i file   .php  nella stessa cartella.
    <details><summary>files</summary>

    - admin.php
    - index.php
    - csv.php
    - database.php
    - Utente.php

    </details>
1.  Istallare e configurare la database
    - Si fornisce il file "traccia.sql"  per creare la database che prevede il codice per creare la tabella "traccia", 7 utenti, e 18 registri temporali di acceso/uscite.
    <details><summary>Più informazione</summary>

    Nomi Utenti disponibili: 
       + ABC    (con rilevazioni)
       + DFG    (con rilevazioni)
       + 123        (senza dati)
       + 456        (senza dati)
       + A1B1   (con rilevazioni)
       + Utente1    (senza dati)
       + Utente2    (senza dati)

    Struttura:
       + `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
       + `utente` char(8) DEFAULT NULL,
       + `ora` datetime DEFAULT NULL,
       + `estado` int(1) DEFAULT NULL,
       + UNIQUE KEY `id` (`id`)


    </details>

1.  Impostare variabile d'ambiente, per collegare la database
    - Tramite var $_ENV oppure:
    - Cambio manuale nel file database.php  (riga 5)
1.  Servire il progetto PHP

## Utilizzo dell'app (Mini Guida - Utente)

Per effettuare l'entrata/uscita allo stabilmento, inserire il proprio codice (es. Utente1) nel campo "utente" della pagina web principale [URL: '/index.php']

Per effettuare un report dei registri, nella pagina pagina web "Admin" [URL: '/admin.php'], compilare il form con nome utente, data inizio e data fine del report. Se la consulta è valida, verrà mostrato nello schermo una lista con i dati. In quest'ultimo caso, l'utente potrà scaricare il report al cliccare sul pulsante "scarica".


## Commenti

Attualmente il progetto cerca di essere semplice. Per questo motivo la struttura database fa utilizzo soltanto di una tabella. Quest'ultimo non è ideale. La prossima versione avrà una tabella `users` e si collegarà con la tabella `traccia` tramite una FK.

L'attuale sistema non prevede un modo di creare nuovi utenti. Si verifica la validità di un utente tramite la tabella `traccia` cercando l'ultimo input dello stesso usuario. In caso che l'utente esiste, ma non ha mai "acceso a lo stabilimento" il valore iniziale di tempo e di status sarà `null`.

In tanto a sicurezza dell'app non si prevede un modo di verifica per l'admin (la URL non è protetta). Pertanto qualsiasi può accedere a l'informazione di qualsiesi utente. 

I campi utente, data-inizio, data-fine, non comprendono una verifica di formato. Per quanto riguarda alla sicurezza dell'app soltanto sono filtrati per cercare di evitare SQL injection. 

Il file CSV scaricabile, è in un formato con punto e virgola come separatore `;` e questo si deve a che in ambienti determinati (es. Excel con impostazione lingua spagnola) si usa la virgola come separatore decimale.