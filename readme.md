#Scalendari

## nomenclatura:
- zone: tipo di zona: A o B
- area: un insieme di siti
- site: un sito (via / piazzetta / località)

## struttura del DB:

#area:
ID(PK)		id
Zone		tipo (A o B)
Description	desrizione
Name		nome dell'area
Transports	tipi di trasporti, in ordina e separati da virgola

#site:
ID(PK)		id
AreaID		-> id@area
Name		nome del sito, termina in (\*) se è divisa in 2 aree
Description	descrizione del sito
Coords		posizione
Calendars	numero di calendari nel sito

#configuration:	tabella che contiene una sola riga per le varie configurazioni
TimeStamp	ultima volta che la tabella è stata modificata
Year		anno a cui è relativo il DB
