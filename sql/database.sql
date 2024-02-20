-- phpMyAdmin SQL Dump
-- version 5.1.1deb5ubuntu1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Creato il: Gen 27, 2024 alle 16:12
-- Versione del server: 10.6.12-MariaDB-0ubuntu0.22.04.1
-- Versione PHP: 8.1.2-1ubuntu2.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rmichelo`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `ArtshowPrenotations`
--

CREATE TABLE `ArtshowPrenotations` (
  `id_artshow` int(11) NOT NULL,
  `id_artist` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `ArtshowPrenotations`
--

INSERT INTO `ArtshowPrenotations` (`id_artshow`, `id_artist`, `time`) VALUES
(1, 2, '2024-01-12 11:32:54'),
(1, 3, '2024-01-06 18:06:27'),
(1, 7, '2024-01-10 09:38:06'),
(1, 8, '2024-01-04 15:30:15'),
(1, 12, '2024-01-04 12:02:03'),
(1, 14, '2024-01-05 22:04:12'),
(2, 11, '2024-01-14 14:43:23'),
(2, 13, '2024-01-15 16:24:41'),
(2, 15, '2024-01-16 12:51:12'),
(2, 16, '2024-01-16 20:07:01'),
(3, 5, '2024-01-22 10:09:23'),
(3, 9, '2024-01-23 21:23:43'),
(3, 10, '2024-01-24 17:55:54'),
(3, 15, '2024-01-24 11:08:09'),
(4, 2, '2024-01-24 15:23:57'),
(4, 5, '2024-01-28 13:56:12'),
(4, 11, '2024-01-25 17:09:35'),
(4, 14, '2024-01-26 22:33:23');

-- --------------------------------------------------------

--
-- Struttura della tabella `Artshows`
--

CREATE TABLE `Artshows` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `image` varchar(100) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `Artshows`
--

INSERT INTO `Artshows` (`id`, `title`, `description`, `image`, `start_date`, `end_date`) VALUES
(1, 'Galleria di Espressioni', 'Lasciati ispirare dalle voci emergenti dell&#x27;arte. Gli studenti artisti trasformano la tela in uno specchio delle emozioni umane, con ritratti che narrano storie uniche e intime. Partecipa a questo viaggio emozionale e sostieni la nuova generazione di talentuosi artisti.', '../uploads/artshows/1.jpg', '2024-03-23', '2024-03-27'),
(2, 'Natura libera', 'Esplora la bellezza selvaggia e incontaminata nella mostra &#x27;Natura Libera&#x27;. Gli studenti artisti danno vita alla natura attraverso dipinti e sculture che catturano la maestosità degli ambienti naturali. Un invito a connettersi con la forza libera e inesplorata del mondo naturale, attraverso gli occhi e le mani dei talenti emergenti.', '../uploads/artshows/2.jpg', '2024-01-19', '2024-01-21'),
(3, 'Arte in Movimento', '&#039;Arte in Movimento&#039; diventa il palcoscenico per gli studenti artisti che esplorano il movimento attraverso i dipinti. Ogni pennellata racconta una storia di creatività e libertà espressiva. Unisciti a noi per celebrare l&#039;arte in evoluzione, con opere che incarnano l&#039;energia e la visione dei talenti emergenti.', '../uploads/artshows/3.jpg', '2024-02-08', '2024-02-11'),
(4, 'Donne in Arte: Giornata Internazionale della Donna', 'In occasione dell&#x27;8 Marzo, la mostra &#x27;Donne in Arte&#x27; rende omaggio alla forza, alla bellezza e all&#x27;arte delle donne. Gli studenti artisti esplorano le molteplici sfaccettature dell&#x27;esperienza femminile attraverso dipinti, sculture e installazioni che riflettono la diversità, la resilienza e la creatività delle donne di ieri, oggi e domani. Un&#x27;occasione per celebrare e onorare il contributo unico delle donne nel mondo dell&#x27;arte e nella società.', '../uploads/artshows/4.jpg', '2024-03-08', '2024-03-18');

-- --------------------------------------------------------

--
-- Struttura della tabella `ArtworkDetails`
--

CREATE TABLE `ArtworkDetails` (
  `id_artwork` int(11) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `ArtworkDetails`
--

INSERT INTO `ArtworkDetails` (`id_artwork`, `image`) VALUES
(5, '../uploads/artworks/5_1.jpg'),
(7, '../uploads/artworks/7_1.jpg'),
(16, '../uploads/artworks/16_1.jpg'),
(16, '../uploads/artworks/16_2.jpg'),
(17, '../uploads/artworks/17_1.jpg'),
(18, '../uploads/artworks/18_1.jpg'),
(19, '../uploads/artworks/19_1.jpg'),
(19, '../uploads/artworks/19_2.jpg'),
(20, '../uploads/artworks/20_1.jpg'),
(21, '../uploads/artworks/21_1.jpg'),
(38, '../uploads/artworks/38_1.jpg'),
(39, '../uploads/artworks/39_1.jpg'),
(46, '../uploads/artworks/46_1.jpg');

-- --------------------------------------------------------

--
-- Struttura della tabella `ArtworkLabels`
--

CREATE TABLE `ArtworkLabels` (
  `id_artwork` int(11) NOT NULL,
  `label` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `ArtworkLabels`
--

INSERT INTO `ArtworkLabels` (`id_artwork`, `label`) VALUES
(1, 'bianconero'),
(1, 'ritratto'),
(1, 'sketch'),
(2, 'bianconero'),
(2, 'realismo'),
(2, 'ritratto'),
(2, 'sketch'),
(3, 'bianconero'),
(3, 'realismo'),
(3, 'ritratto'),
(3, 'sketch'),
(4, 'bianconero'),
(4, 'realismo'),
(4, 'ritratto'),
(4, 'sketch'),
(5, 'natura'),
(5, 'paesaggio'),
(5, 'sketch'),
(6, 'astrazione'),
(6, 'bianconero'),
(6, 'contemporaneo'),
(6, 'sketch'),
(7, 'ritratto'),
(8, 'ritratto'),
(9, 'architettura'),
(9, 'natura'),
(9, 'paesaggio'),
(10, 'architettura'),
(10, 'natura'),
(11, 'architettura'),
(12, 'architettura'),
(13, 'natura'),
(14, 'architettura'),
(14, 'mare'),
(15, 'architettura'),
(15, 'natura'),
(15, 'paesaggio'),
(16, 'dipinto'),
(16, 'natura'),
(16, 'notte'),
(16, 'paesaggio'),
(16, 'sfumature'),
(17, 'dipinto'),
(17, 'mare'),
(17, 'paesaggio'),
(17, 'sfumature'),
(18, 'dipinto'),
(18, 'natura'),
(18, 'paesaggio'),
(18, 'sfumature'),
(19, 'dipinto'),
(19, 'mare'),
(19, 'notte'),
(19, 'sfumature'),
(20, 'dipinto'),
(20, 'natura'),
(20, 'paesaggio'),
(20, 'sfumature'),
(21, 'dipinto'),
(21, 'mare'),
(21, 'paesaggio'),
(21, 'sfumature'),
(22, 'architettura'),
(22, 'bianconero'),
(22, 'dipinto'),
(22, 'notte'),
(22, 'sfumature'),
(23, 'architettura'),
(23, 'bianconero'),
(23, 'dipinto'),
(23, 'notte'),
(23, 'sfumature'),
(24, 'bianconero'),
(24, 'minimalismo'),
(24, 'sketch'),
(25, 'bianconero'),
(25, 'sketch'),
(26, 'bianconero'),
(26, 'minimalismo'),
(26, 'sketch'),
(27, 'bianconero'),
(27, 'realismo'),
(27, 'ritratto'),
(27, 'sketch'),
(28, 'bianconero'),
(28, 'minimalismo'),
(28, 'ritratto'),
(28, 'sketch'),
(29, 'bianconero'),
(29, 'mare'),
(29, 'sfumature'),
(29, 'sketch'),
(30, 'bianconero'),
(30, 'natura'),
(30, 'sfumature'),
(30, 'sketch'),
(31, 'dipinto'),
(31, 'mare'),
(31, 'natura'),
(31, 'sfumature'),
(32, 'dipinto'),
(32, 'mare'),
(32, 'movimento'),
(32, 'natura'),
(32, 'sfumature'),
(33, 'bianconero'),
(33, 'mare'),
(33, 'sketch'),
(34, 'dipinto'),
(34, 'natura'),
(34, 'paesaggio'),
(34, 'sfumature'),
(35, 'dipinto'),
(35, 'natura'),
(35, 'paesaggio'),
(35, 'sfumature'),
(36, 'bianconero'),
(36, 'minimalismo'),
(36, 'sketch'),
(37, 'bianconero'),
(37, 'minimalismo'),
(37, 'sketch'),
(38, 'dipinto'),
(38, 'inverno'),
(38, 'natura'),
(38, 'paesaggio'),
(39, 'astrazione'),
(39, 'contemporaneo'),
(39, 'ritratto'),
(39, 'sfumature'),
(40, 'minimalismo'),
(40, 'ritratto'),
(40, 'sketch'),
(41, 'sfumature'),
(41, 'sketch'),
(42, 'dipinto'),
(42, 'natura'),
(42, 'paesaggio'),
(43, 'dipinto'),
(43, 'natura'),
(43, 'paesaggio'),
(43, 'sfumature'),
(44, 'natura'),
(44, 'oggetto'),
(45, 'natura'),
(45, 'oggetto'),
(46, 'natura'),
(46, 'oggetto');

-- --------------------------------------------------------

--
-- Struttura della tabella `Artworks`
--

CREATE TABLE `Artworks` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `main_image` varchar(100) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `height` float DEFAULT NULL,
  `width` float DEFAULT NULL,
  `length` float DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `upload_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_artist` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `Artworks`
--

INSERT INTO `Artworks` (`id`, `title`, `main_image`, `description`, `height`, `width`, `length`, `start_date`, `end_date`, `upload_time`, `id_artist`) VALUES
(1, 'Gisella', '../uploads/artworks/1_0.jpg', 'È rappresentata l&#x27;essenza della figura femminile in un momento di intimità e riflessione. Le linee semplici e non colorate evidenziano la purezza e la semplicità dell&#x27;espressione artistica, lasciando spazio all&#x27;immaginazione e alla suggestione. La signora, delicatamente posizionata su un divanetto, diventa il fulcro di un&#x27;atmosfera silenziosa e contemplativa. L&#x27;opera invita lo spettatore a riflettere sulla natura effimera dell&#x27;esistenza e sulla potenza evocativa della figura umana.', 42, 55, 0, '2010-07-06', '2015-04-10', '2024-01-24 11:38:14', 2),
(2, 'La madre che lavora all&#x27;uncinetto', '../uploads/artworks/2_0.jpg', 'Un&#x27;anziana donna è raffigurata in un momento intimo e quotidiano, immersa nel gesto antico e meditativo del cucito. Le linee essenziali e la mancanza di colorazione amplificano la profondità emotiva e la semplicità dell&#x27;atto rappresentato. La cucina, come sfondo, aggiunge un senso di familiarità e comfort, sottolineando la routine e la maestria acquisite nel tempo. L&#x27;opera invita lo spettatore a riflettere sulle connessioni tra memoria, tempo e gesti semplici ma evocativi.', 56, 43, 0, '2019-06-25', '2019-08-29', '2024-01-25 14:10:24', 2),
(3, 'La madre davanti al tavolo con le forbici', '../uploads/artworks/3_0.jpg', 'Con lo sguardo del soggetto, fisso su un punto indefinito, ho voluto evocare un senso di contemplazione profonda e introspezione. La mancanza di colori amplifica l&#x27;atmosfera di mistero e intimità, focalizzando l&#x27;attenzione sulle linee e sulle ombre che delineano la figura e l&#x27;ambiente circostante. L&#x27;opera invita lo spettatore a riflettere sulle connessioni silenziose che legano ogni individuo al proprio universo interiore.', 56, 42, 0, '2012-05-16', '2020-06-20', '2024-01-26 08:26:19', 2),
(4, 'Maria Sacchi che legge', '../uploads/artworks/4_0.jpg', 'Con la luce e l&#x27;ombra che delineano delicatamente i contorni, ho cercato di evocare un senso di tranquillità e concentrazione. La cucina come ambiente offre un contesto familiare e quotidiano, mentre il gesto di leggere sottolinea la continuità e la ricchezza dell&#x27;esperienza umana attraverso le generazioni. L&#x27;opera invita lo spettatore a riflettere sulla bellezza della contemplazione e sull&#x27;importanza dell&#x27;apprendimento e della connessione con il mondo attraverso la letteratura.', 48, 32, 0, '2017-07-05', '2018-02-26', '2024-01-26 10:20:28', 2),
(5, 'Giovane sulla riva del fiume; Una scena Wagneriana', '../uploads/artworks/5_0.jpg', 'Ho voluto catturare il momento magico in cui un uomo si perde nella contemplazione del tramonto sul molo di un laghetto. Le tonalità calde del cielo si mescolano con i riflessi dell&#x27;acqua, creando un&#x27;atmosfera eterea e suggestiva. L&#x27;uomo, silenziosamente immerso nella bellezza della natura, diventa il fulcro di un&#x27;opera che invita alla riflessione e alla connessione con gli elementi. L&#x27;opera celebra la serenità del momento presente e l&#x27;armonia tra l&#x27;individuo e il paesaggio.', 29, 21, 0, '2018-10-20', '2020-01-23', '2024-01-26 12:11:40', 2),
(6, 'Stato mentale: quelli che vanno', '../uploads/artworks/6_0.jpg', 'Con questa composizione di linee e curve nere su sfondo bianco, ho cercato di esplorare il concetto della dualità e dell&#x27;interconnessione. Le linee rappresentano le strade lineari e prevedibili della nostra esistenza, mentre le curve simboleggiano le impreviste e meravigliose svolte del destino. Nonostante l&#x27;apparente caos, l&#x27;intero disegno è un insieme armonioso, sottolineando che ogni tratto, per quanto disorientante possa sembrare, contribuisce alla bellezza e complessità dell&#x27;esperienza umana.', 31, 43, 0, '2013-10-17', '2017-07-24', '2024-01-27 15:37:04', 2),
(7, 'Chase, New York, American League', '../uploads/artworks/7_0.jpg', 'L&#x27;energia e la determinazione di un giocatore di baseball nel momento cruciale del lancio. La tensione palpabile nel suo corpo e lo sguardo concentrato suggeriscono la passione e l&#x27;arte che sottendono questo sport. L&#x27;opera celebra la precisione, la forza e la grazia del movimento.', 6, 3, 0, '2020-09-18', '2023-02-03', '2024-01-27 12:16:34', 3),
(8, 'Murphy, Philadelphia, American League', '../uploads/artworks/8_0.jpg', 'L&#x27;essenza della potenza e della determinazione di un giocatore di baseball nel momento in cui impugna la mazza. La postura concentrata e pronta all&#x27;azione del giocatore evoca la tensione e l&#x27;anticipazione del momento decisivo. L&#x27;opera celebra la forza fisica, la strategia e la maestria dell&#x27;uomo nello sport.', 6, 3, 0, '2011-02-09', '2020-07-15', '2024-01-26 06:25:25', 3),
(9, 'La fattoria americana', '../uploads/artworks/9_0.jpg', 'Attraverso questa rappresentazione, ho voluto esplorare l&#x27;armonia e la connessione tra l&#x27;architettura umana e la natura circostante. La casa di legno, avvolta e protetta dal bosco, simboleggia l&#x27;integrazione e il rispetto reciproco tra l&#x27;uomo e l&#x27;ambiente. Le tonalità terrose e le texture naturali sottolineano la fusione tra l&#x27;opera dell&#x27;uomo e il paesaggio naturale, creando un&#x27;atmosfera di serenità e equilibrio.', 6, 10, 0, '2012-08-30', '2018-04-15', '2024-01-26 10:14:34', 4),
(10, 'Cottage americano', '../uploads/artworks/10_0.jpg', 'Ho voluto catturare la quiete e l&#x27;isolamento sereno di un cottage di legno immerso nella natura. In lontananza, il boschetto aggiunge profondità e dimensione, offrendo un contrasto tra la costruzione umana e l&#x27;ambiente circostante. Le tonalità calde del legno si armonizzano con i verdi sfumati del bosco, creando un&#x27;atmosfera accogliente e avvolgente. L&#x27;opera invita lo spettatore a riflettere sulla bellezza semplice e rassicurante dell&#x27;habitat naturale.', 6, 10, 0, '2018-07-27', '2020-01-19', '2024-01-25 13:04:08', 4),
(11, 'La casa spagnola', '../uploads/artworks/11_0.jpg', 'Attraverso questa rappresentazione, ho voluto esplorare il concetto di individualità e unicità architettonica in un contesto tradizionale. La casa, con i suoi tetti e muri ricurvi, emerge come un elemento distintivo e intrigante accanto a strutture più convenzionali. Questa diversità architettonica sottolinea l&#x27;importanza della personalità e dell&#x27;originalità in un mondo spesso omogeneo.', 6, 10, 0, '2010-03-13', '2016-06-13', '2024-01-26 09:30:44', 4),
(12, 'La casa a Calcutta', '../uploads/artworks/12_0.jpg', 'Ho voluto evocare l&#x27;essenza e la maestosità dei palazzi antichi, reinterpretando il fascino intrinseco dell&#x27;architettura storica. L&#x27;edificio in mattoni bianchi si distingue per la sua particolarità e dettagli artigianali, trasportando lo spettatore in un viaggio attraverso epoche passate e stili distintivi. La scelta dei mattoni bianchi enfatizza la purezza delle linee e la raffinatezza delle proporzioni, creando un&#x27;atmosfera di eleganza senza tempo.', 6, 10, 0, '2016-03-05', '2023-09-21', '2024-01-26 11:22:34', 4),
(13, 'Accampamento indiano', '../uploads/artworks/13_0.jpg', 'Ho voluto catturare l&#x27;atmosfera e la spiritualità di un accampamento indiano, simbolo di tradizioni ancestrali e connessione con la natura. Le tende, disposte con armonia, evocano un senso di comunità e rispetto per la terra. L&#x27;opera celebra la ricchezza culturale e la saggezza dei popoli nativi, invitando lo spettatore a immergersi nella bellezza e nella profondità delle loro tradizioni.', 6, 10, 0, '2019-08-13', '2020-03-05', '2024-01-26 07:40:19', 4),
(14, 'Casa nel Mar Nero', '../uploads/artworks/14_0.jpg', 'Attraverso questa rappresentazione, ho voluto esplorare l&#x27;interazione tra l&#x27;umanità e l&#x27;ambiente marino, evocando la resilienza e l&#x27;adattabilità dell&#x27;essere umano. La casa palafitta, solitaria e imponente, emerge come simbolo di resistenza e connessione con il mare. Circondata dalle acque, sottolinea il legame indissolubile tra l&#x27;uomo e l&#x27;elemento naturale, ma anche la fragilità e la forza insite in tale rapporto.', 6, 10, 0, '2021-05-27', '2022-10-07', '2024-01-26 12:40:19', 4),
(15, 'Casa a Palmetto', '../uploads/artworks/15_0.jpg', 'Attraverso questa rappresentazione, ho voluto catturare l&#x27;armonia tra l&#x27;architettura tradizionale e l&#x27;ambiente naturale. La casa, di paglia e delicatamente posizionata sopra un laghetto, simboleggia la simbiosi tra l&#x27;umanità e la natura. La fragilità apparente del materiale contrasta con la sua resistenza, sottolineando la saggezza e l&#x27;ingegnosità delle antiche tecniche costruttive.', 6, 10, 0, '2016-01-15', '2020-06-14', '2024-01-26 18:28:15', 4),
(16, 'Panorama con Stelle', '../uploads/artworks/16_0.jpg', 'I contorni scuri del bosco emergono come sentinellesche custodi della notte, mentre il cielo stellato irradia una luce soave e misteriosa, avvolgendo tutto in un&#x27;aura di magia e meraviglia. L&#x27;opera invita lo spettatore a immergersi nella profondità della natura e a contemplare l&#x27;infinita vastità dell&#x27;universo, sottolineando la bellezza e la serenità delle notti stellate e il mistero che avvolge il nostro cosmo.', 24, 32, 0, '2012-06-23', '2013-09-13', '2024-01-27 09:26:34', 5),
(17, 'La Giudecca', '../uploads/artworks/17_0.jpg', 'Attraverso questa rappresentazione, ho voluto catturare l&#x27;essenza incantevole di Venezia, unendo la vivacità delle imbarcazioni in primo piano con la silhouette distintiva dell&#x27;isola della Giudecca in lontananza. Le acque riflettenti e i contorni distinti degli edifici veneziani evocano un senso di romanticismo e storia.', 17, 24, 0, '2015-03-25', '2017-03-26', '2024-01-26 13:30:19', 5),
(18, 'Panorama', '../uploads/artworks/18_0.jpg', 'La fusione armoniosa e poetica tra natura e paesaggio. Gli alberi, il cielo e il prato si mescolano in una danza cromatica, creando una tavolozza vibrante di tonalità che si sfumano e si intrecciano. Questa fusione evoca una sensazione di unità e continuità, sottolineando la connessione profonda e indissolubile tra gli elementi naturali.', 17, 24, 0, '2010-01-21', '2014-04-29', '2024-01-26 06:40:27', 5),
(19, 'Venezia: Notte della festa del Redentore', '../uploads/artworks/19_0.jpg', 'L&#x27;intima connessione tra l&#x27;individuo e l&#x27;atmosfera magica di Venezia di notte. La donna, seduta davanti al tavolo, si fonde armoniosamente con l&#x27;ambiente circostante, riflettendo e assorbendo le tonalità mutevoli della città lagunare. La serenità e la contemplazione della figura contrastano con l&#x27;energia vibrante e misteriosa della notte veneziana, creando un&#x27;atmosfera di mistero e fascino.', 41, 53, 0, '2021-02-17', '2023-09-07', '2024-01-25 10:32:34', 5),
(20, 'Cap Nègre', '../uploads/artworks/20_0.jpg', 'L&#x27;armoniosa danza dei colori e delle forme tra natura e paesaggio. I due cespugli in primo piano fungono da ancoraggio, mentre il lago sereno con le due barchette evoca una sensazione di tranquillità e riflessione. La presenza imponente del monte in lontananza aggiunge profondità e maestosità alla scena. L&#x27;interplay cromatico tra i diversi elementi crea un effetto visivo dinamico, invitando lo spettatore a perdersi nella bellezza e nella serenità della natura.', 25, 42, 0, '2018-02-05', '2021-12-01', '2024-01-26 09:32:16', 5),
(21, 'Barche vicino Venezia', '../uploads/artworks/21_0.jpg', 'Attraverso questa rappresentazione, ho voluto esaltare l&#x27;incanto e la vivacità del porto veneziano. Le barche, in perfetta simbiosi con una elegante gondola, si stagliano con grazia sull&#x27;acqua, catturando l&#x27;essenza della vita marittima e fluviale di Venezia. I colori dell&#x27;acqua, vibranti e cangianti, danzano come riflessi luminosi, creando un&#x27;atmosfera di magia e movimento.', 17, 24, 0, '2018-07-09', '2022-05-26', '2024-01-26 12:21:35', 5),
(22, 'Perkins Harnly', '../uploads/artworks/22_0.jpg', 'Ho voluto esplorare la tensione visiva tra oscurità e luminosità, tra passato e presente. Il vecchio palazzo, imponente e avvolto nella tonalità dominante del nero, emerge come testimonianza silente di epoche passate. Il cielo nero intensifica il contrasto con le figure bianche, creando una dinamica visiva che sottolinea la forza e l&#x27;essenza della scena. L&#x27;uso sapiente del bianco illumina e definisce le forme, evocando un senso di mistero e profondità.', 56, 46, 0, '2022-08-29', '2023-03-29', '2024-01-26 05:37:33', 6),
(23, 'Teatro della quattordicesima strada', '../uploads/artworks/23_0.jpg', 'È presente l&#x27;incontro dell&#x27;antico con il contemporaneo, un dialogo visivo tra epoche e stili architettonici. Il teatro moderno, con chiari riferimenti all&#x27;architettura romana attraverso le sue colonne e il frontone distintivo, si erge con imponenza e maestosità. Il predominio del colore grigio e dello sfondo scuro enfatizza la solennità dell&#x27;edificio, mentre evidenzia le linee e i dettagli architettonici. L&#x27;opera invita lo spettatore a riflettere sulla continuità dell&#x27;arte e della storia.', 44, 56, 0, '2019-07-03', '2021-01-15', '2024-01-26 08:34:02', 6),
(24, 'Uomo con la scatola', '../uploads/artworks/24_0.jpg', 'Attraverso questa rappresentazione, ho voluto catturare l&#x27;essenza del periodo universitario, un momento di speranza, aspirazioni e desideri per il futuro. L&#x27;uomo abbozzato, con il suo scatolone marcato All Good Wishes, simboleggia il passaggio e la transizione, portando con sé gli auguri positivi per tutti gli studenti. Questa figura incarna la determinazione, la speranza e la solidarietà di fronte alle sfide accademiche.', 11, 10, 0, '2016-12-04', '2020-01-01', '2024-01-26 11:24:19', 7),
(25, 'Uomo settecentesco con un cartello', '../uploads/artworks/25_0.jpg', 'Attraverso questa rappresentazione, ho voluto evocare il fascino e la tradizione dell&#x27;era settecentesca, mescolandola con un messaggio universale di speranza e incoraggiamento. L&#x27;uomo elegantemente vestito di abiti della sua epoca, con il suo cartello che recita All Good Wishes, simboleggia un ponte temporale tra passato e presente, offrendo un augurio di buona fortuna a tutti gli studenti universitari impegnati con la sessione d&#x27;esami.', 21, 14, 0, '2022-05-25', '2023-02-28', '2024-01-26 14:37:43', 7),
(26, 'Giovane ragazza con un cesto di All Good Wishes', '../uploads/artworks/26_0.jpg', 'Ho voluto catturare l&#x27;innocenza e la generosità di un gesto semplice ma profondo: una bambina offre un cesto ricolmo di All Good Wishes agli studenti in periodo di esami. Questo simbolico dono rappresenta la speranza, il sostegno e l&#x27;incoraggiamento in un momento cruciale della loro vita accademica. L&#x27;opera vuole evocare un senso di comunità e solidarietà.', 11, 8, 0, '2013-07-18', '2014-01-16', '2024-01-26 07:12:13', 7),
(27, 'Uomo malinconico', '../uploads/artworks/27_0.jpg', 'In questa rappresentazione in bianco e nero, ho cercato di catturare l&#x27;intensità della solitudine e del male di vivere. Il soggetto, un signore profondamente assorto nei suoi pensieri, si appoggia al tavolo: il volto velato da un&#x27;espressione di malinconia. Attraverso tratti fini e un gioco di chiaro-scuro, ho voluto evocare un&#x27;atmosfera di introspezione e silenziosa contemplazione, dove la solitudine dell&#x27;uomo viene impersonificata dal soggetto.', 21, 16, 0, '2014-04-10', '2022-07-03', '2024-01-25 10:36:26', 8),
(28, 'Il viandante', '../uploads/artworks/28_0.jpg', 'Attraverso questo disegno, ho voluto rappresentare la profonda solitudine che affligge l&#x27;essere umano. Il signore, vestito di stracci, vagabonda con un vaso vuoto, simbolo di vuoto interiore e disillusione. Sostenendosi con un bastone, la sua figura riflette la fragilità e l&#x27;abbandono dell&#x27;individuo nella vastità del mondo, evidenziando il contrasto tra l&#x27;essenzialità della vita e la desolazione dell&#x27;esistenza.', 13, 8, 0, '2010-09-14', '2014-06-03', '2024-01-25 14:25:36', 8),
(29, 'Barca arenata', '../uploads/artworks/29_0.jpg', 'Ho voluto esplorare il concetto del tempo implacabile e della transitorietà della vita. La barca, mezza distrutta e arenata, rappresenta le nostre ambizioni e speranze che, con il passare degli anni, possono sgretolarsi come legno esposto agli elementi. La palizzata deteriorata accanto, sottolinea il ciclo naturale di crescita e declino, suggerendo che tutto ciò che è costruito può, con il tempo, disfarsi e fondersi nuovamente con l&#x27;ambiente circostante.', 18, 24, 0, '2018-04-29', '2023-11-16', '2024-01-27 15:37:04', 9),
(30, 'Arco di Pola', '../uploads/artworks/30_0.jpg', 'La fragilità delle grandi civiltà e delle loro opere monumentali di fronte all&#x27;inevitabile avanzare del tempo. L&#x27;arco, una volta simbolo di grandezza e potenza, ora giace distrutto, testimonianza mutevole delle ere passate. La cittadina circostante, desolata e abbandonata, evoca l&#x27;effimera natura delle conquiste umane, ricordando che anche le più solide civilizzazioni possono essere sommerse dal flusso inarrestabile della storia.', 31, 47, 0, '2013-03-15', '2018-12-19', '2024-01-26 10:26:32', 9),
(31, 'Scorcio marino', '../uploads/artworks/31_0.jpg', 'I tre vascelli, maestosi e imponenti, fluttuano dolcemente sulle acque placide, creando una scena di pace e stabilità. L&#x27;acqua del mare, calma e riflettente, funge da specchio per il cielo, enfatizzando la quiete e la tranquillità dell&#x27;ambiente. Quest&#x27;opera invita lo spettatore a immergersi in un momento di quiete, lontano dal tumulto quotidiano, e a riflettere sulla bellezza semplice e rassicurante della natura.', 6, 9, 0, '2021-12-11', '2022-04-30', '2024-01-25 11:32:33', 10),
(32, 'Nord Est', '../uploads/artworks/32_0.jpg', 'L&#x27;onda del mare, potente e imponente, si infrange con forza contro la scogliera, simbolo della resistenza e dell&#x27;ineluttabilità della vita. Le pennellate dinamiche e fluide riflettono il movimento frenetico dell&#x27;acqua, mentre i contrasti di colore evocano l&#x27;intensità dell&#x27;evento. Quest&#x27;opera invita lo spettatore a contemplare la bellezza e la potenza del mare, sottolineando la fragilità dell&#x27;uomo di fronte alla maestosità della natura.', 88, 128, 0, '2020-04-28', '2020-10-17', '2024-01-25 11:42:44', 10),
(33, 'Paesaggio marino con barche', '../uploads/artworks/33_0.jpg', 'Le barche, in un balletto silenzioso, solcano le acque calme, mentre un cielo azzurro e sereno abbraccia l&#x27;orizzonte. Il raggio di sole che permea la scena aggiunge un tocco di magia, illuminando ogni dettaglio e trasmettendo una sensazione di pace e tranquillità. Quest&#x27;opera invita lo spettatore a immergersi in un momento di quiete, celebrando la bellezza e la semplicità della natura in tutto il suo splendore.', 19, 29, 0, '2021-07-03', '2022-08-14', '2024-01-26 06:24:46', 10),
(34, 'Scorcio sul fiume', '../uploads/artworks/34_0.jpg', 'Gli alberi, immobili e silenziosi, testimoniano la quiete assoluta di un ambiente incontaminato. Il calmo laghetto in lontananza riflette il cielo azzurro, aggiungendo profondità e armonia alla composizione. Senza la minima brezza a turbare la scena, l&#x27;opera evoca un senso di pace profonda, invitando lo spettatore a perdersi nella bellezza semplice e rassicurante della natura.', 76, 63, 0, '2014-08-08', '2014-09-21', '2024-01-24 09:29:30', 11),
(35, 'Nel prato', '../uploads/artworks/35_0.jpg', 'Le due ragazze, sedute in perfetta quiete sul morbido prato, sono il fulcro di un&#x27;atmosfera intatta e pacifica. Gli alberi immobili e il sole radiante sottolineano la pace che pervade l&#x27;ambiente circostante. Con la natura come cornice, l&#x27;opera invita lo spettatore a immergersi in un momento di pura tranquillità e contemplazione, celebrando la bellezza semplice e autentica della vita all&#x27;aria aperta.', 81, 65, 0, '2013-07-24', '2015-10-25', '2024-01-25 11:32:29', 11),
(36, 'Scheletro con divisa militare', '../uploads/artworks/36_0.jpg', 'La pressione e il peso emotivo che molti studenti sperimentano nel percorso educativo. Lo scheletro, vestito in uniforme da soldato con elmetto e fucile, simboleggia la battaglia interiore di uno studente esausto e demoralizzato dalle continue sfide accademiche. È la tensione tra la passione per l&#x27;apprendimento e il crescente stress dell&#x27;ambiente universitario, dove non si riconoscono le pressioni mentali ed emotive che molti giovani affrontano nella ricerca della conoscenza.', 7, 6, 0, '2018-02-02', '2021-08-31', '2024-01-25 13:32:36', 12),
(37, 'Danza degli scheletri', '../uploads/artworks/37_0.jpg', 'Attraverso questo disegno, ho voluto rappresentare l&#039;incessante frenesia e il senso di spossatezza che pervade l&#039;umanità moderna, spesso sacrificata sull&#039;altare dell&#039;ideologia del lavoro incessante. Gli scheletri, in una danza caotica di attività, simbolizzano la perdita dell&#039;autenticità e della vitalità umana nel perseguire un ideale distorto di produttività. L&#039;opera invita a una riflessione profonda sull&#039;equilibrio tra vita e lavoro.', 13, 19, NULL, '2023-05-11', '2023-06-06', '2024-01-25 09:32:36', 12),
(38, 'Cervi e albero contro il tramonto', '../uploads/artworks/38_0.jpg', 'Ho voluto esplorare la connessione tra la natura e la maestosità del cervo come simbolo di purezza e libertà. Il cervo, in primo piano, emerge come figura centrale di forza e vulnerabilità, mentre l&#x27;albero spoglio rappresenta la ciclicità della vita e della stagione. Gli altri animali in lontananza offrono una prospettiva di profondità e comunità, enfatizzando l&#x27;armonia e l&#x27;interconnessione tra gli individui nella natura.', 27, 38, 0, '2014-07-25', '2020-07-08', '2024-01-25 05:11:17', 13),
(39, 'Busto di un uomo', '../uploads/artworks/39_0.jpg', 'L&#x27;intensità emotiva di un uomo travolto dall&#x27;ansia e dalla frenesia della vita moderna. L&#x27;espressione agitata del suo busto, delineata dai toni dominanti di rosso, bianco e nero, evoca un senso di conflitto interiore e disorientamento. Il rosso simboleggia la passione e l&#x27;agitazione, mentre il bianco e il nero accentuano il contrasto tra luci e ombre dell&#x27;esistenza umana.', 29, 20, 0, '2014-11-16', '2017-02-06', '2024-01-25 09:11:17', 14),
(40, 'Ritratto di donna', '../uploads/artworks/40_0.jpg', 'Attraverso questo dipinto, ho voluto celebrare la femminilità e l&#x27;individualità attraverso un gioco di contrasti e colori distintivi. La donna, seduta con eleganza, indossa un cappotto bianco e nero e un cappello assortito, evocando un senso di sofisticazione e stile. Tuttavia, è il colore arancione intenso dei suoi capelli a dominare la composizione, simboleggiando passione, energia e unicità.', 14, 8, 0, '2011-01-29', '2012-12-24', '2024-01-24 10:22:17', 14),
(41, 'Prima fila', '../uploads/artworks/41_0.jpg', 'Atmosfera intima e suggestiva di una sala cinematografica in un momento di quiete. Con poche persone sparse nella sala, la luce proveniente dalla proiezione diventa l&#x27;unico punto focale, creando un&#x27;aura di mistero e attesa nell&#x27;illuminare i volti dei presenti. L&#x27;opera invita lo spettatore a immergersi nell&#x27;esperienza unica del cinema, sottolineando la magia e l&#x27;isolamento temporaneo che si vive all&#x27;interno di questo spazio dedicato all&#x27;arte e alla narrazione visiva.', NULL, NULL, NULL, '2021-04-15', '2022-06-16', '2024-01-25 10:25:34', 14),
(42, 'Pini marittimi di Villa Borghese, Roma', '../uploads/artworks/42_0.jpg', 'Ho voluto catturare l&#x27;essenza serena e incantevole dello scorcio del Giardino di Villa Borghese, a Roma. Con linee di colore delicate e toni sottili, ho cercato di trasmettere la tranquillità e la bellezza naturalistica di questo luogo iconico.', 41, 28, 0, '2010-08-11', '2022-06-22', '2024-01-26 13:21:16', 15),
(43, 'Vista di Toledo', '../uploads/artworks/43_0.jpg', 'Attraverso questo dipinto, ho voluto evocare l&#x27;atmosfera di mistero e incertezza che avvolge una tranquilla cittadina. Il cielo scuro che incombe sulla scena suggerisce un senso di imminente cambiamento o di tensione latente, creando un contrasto palpabile con la quiete apparente del luogo.', 121, 108, 0, '2021-11-29', '2022-07-26', '2024-01-24 10:32:35', 15),
(44, 'Un Vaso', '../uploads/artworks/44_0.jpg', 'Ho voluto esaltare la delicatezza e la semplicità della natura trasformando questo vasetto di vetro. Ogni fiorellino dipinto sulla superficie cristallina evoca un senso di fragilità e bellezza effimera. L&#x27;opera invita lo spettatore a riflettere sulla capacità dell&#x27;arte di catturare la grazia sottile e l&#x27;essenza intrinseca della vita, celebrando la meraviglia nascosta nei dettagli più piccoli e trascurati del nostro quotidiano.', 18, 8, 8, '2016-02-02', '2022-11-09', '2024-01-25 11:29:30', 16),
(45, 'Il Vaso', '../uploads/artworks/45_0.jpg', 'La delicatezza e la bellezza dei fiori si fonde alla fragilità del vetro. Ogni pennellata riflette la cura e l&#x27;attenzione dedicate alla creazione di un&#x27;opera d&#x27;arte unica e affascinante. L&#x27;opera invita lo spettatore a contemplare la fusione di artigianalità e estetica, sottolineando la capacità di trasformare un oggetto quotidiano in una testimonianza tangibile della bellezza della natura.', 19, 7, 7, '2013-05-04', '2019-03-17', '2024-01-25 13:35:42', 16),
(46, 'Ancora Vaso', '../uploads/artworks/46_0.jpg', 'La grazia senza tempo dei fiori si sposa con la delicatezza della porcellana. Ogni dettaglio del vaso è un&#x27;armoniosa sinfonia di eleganza e fragilità. Le curve sottili e le sfumature cromatiche creano un capolavoro unico, intessuto con la dedizione e l&#x27;arte di creare un oggetto di bellezza intramontabile. Il vaso invita chi lo osserva a immergersi in un mondo di raffinatezza e a contemplare la fusione di maestria artigianale ed estetica, trasformando il quotidiano in una testimonianza tangibile della sublime bellezza floreale.', 27, 0, 0, '2019-02-26', '2022-10-17', '2024-01-27 09:21:42', 16);

-- --------------------------------------------------------

--
-- Struttura della tabella `Labels`
--

CREATE TABLE `Labels` (
  `label` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `Labels`
--

INSERT INTO `Labels` (`label`) VALUES
('architettura'),
('astrazione'),
('bianconero'),
('bronzo'),
('contemporaneo'),
('digitale'),
('dipinto'),
('inverno'),
('mare'),
('marmo'),
('minimalismo'),
('movimento'),
('natura'),
('notte'),
('oggetto'),
('paesaggio'),
('realismo'),
('ritratto'),
('scultura'),
('sfumature'),
('sketch');

-- --------------------------------------------------------

--
-- Struttura della tabella `Users`
--

CREATE TABLE `Users` (
  `id` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(30) NOT NULL,
  `lastname` varchar(30) NOT NULL,
  `is_admin` tinyint(1) NOT NULL,
  `image` varchar(100) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `birth_place` varchar(30) DEFAULT NULL,
  `biography` varchar(1000) DEFAULT NULL,
  `experience` varchar(1000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `Users`
--

INSERT INTO `Users` (`id`, `username`, `password`, `name`, `lastname`, `is_admin`, `image`, `birth_date`, `birth_place`, `biography`, `experience`) VALUES
(1, 'admin', '$2b$12$9sm6jSUZr5up9OBU3GsTOewCMm4NUj5xK1EFwviI76xxAAcmTm.Je', 'Admin', 'Admin', 1, NULL, NULL, NULL, NULL, NULL),
(2, 'ant-ali14', '$2b$12$xQdT0EE1NhAXz11yOIH3oeTszOVZ/0YbrXyMVZ6kP/V7kaF3HkMUW', 'Antonio', 'Alighieri', 0, '../uploads/users/2.jpg', '2002-06-24', 'Bagnara Calabra', 'Studio Storia e tutela dei beni artistici e culturali. Appassionato di tatuaggi: un&#039;immagine sulla nostra pelle racconta di noi più di qualsiasi altra cosa. Nel tempo libero mi piace suonare il pianoforte, dipingere, disegnare e ascoltare musica. Sogno di diventare divulgatore artistico a livello nazionale.', 'Ho frequentato il liceo artistico Modigliani di Padova. Mi piace rappresentare la natura e gli animali, ma anche ritrarre persone dal vivo. Sono abile nella pittura ad olio e con colori a tempera, ma mi diverte di più disegnare. L&#039;arte è una parte fondamentale della mia vita, e voglio condividere la mia passione con tutti i miei colleghi studenti.'),
(3, 'gustav.peder', '$2b$12$9stUizf0lZOBOYiqWRsJQ.5M9rJGjNqn4FRvYDAmxZWYAi/C4sthm', 'Gustavo', 'Pederiva', 0, '../uploads/users/3.jpg', '1998-08-27', 'Litta Parodi', 'Scienze e tecniche dell&#x27;attività motoria preventiva e adattata presso UniPD. Baseball addicted. Interbase dei Castelfranco Dragons: tutta la mia vita appartiene ad una pallina e ad una mazza. Quando non mi alleno né studio, disegno per rilassarmi e rigenerare la mente.', 'Sono un autodidatta a cui piace disegnare per passare il tempo e divertirsi, ma non ho mai mostrato a nessuno questo lato di me. Ritraggo principalmente ex giocatori dello sport più bello del mondo. Voglio condividere le mie opere per promuovere il gioco baseball con tutti i miei coetanei.'),
(4, 'renat_gin', '$2b$12$daSW9z6SyB2/IAONUlVUWOEgQ8aepTb.DN6mer4YtSc2pMTSy6wQK', 'Renata', 'Ginese', 0, '../uploads/users/4.jpg', '2000-03-30', 'Golferenzo', 'Studentessa al quarto anno di Ingegneria edile e architettura. Amante del design innovativo e della sostenibilità. Quando non sono immersa nei progetti accademici, mi trovi a osservare i palazzi del centro storico di Padova. Quando non disegno case... dipingo case.', 'Le ore passate a disegnare tavole mi hanno trasmesso la passione per l&#039;arte. Non c&#039;è nulla di più magico della fusione tra natura e architettura. Voglio mostrare a tutti le mie creazioni per mostrare la bellezza, unica e inimitabile, che ogni edificio nasconde dentro di sé.'),
(5, 'lando.buson', '$2b$12$BCVZ1mnNPF6Wma5ij1Ftv.npI8cJNHr2xFCv/ywo5vKyOx38xvT8K', 'Lando', 'Busoni', 0, '../uploads/users/5.jpg', '1999-01-18', 'Palazzolo Dello Stella', 'Studente di Scienze naturali e ambientali. Amante dei sentieri selvaggi e dei tramonti mozzafiato. Innamorato del canto degli uccelli e del fruscio delle foglie. Non puoi dire di sapere cos&#039;è la bellezza se non sei mai stato a Venezia. Pittore amatoriale e musicista autodidatta nel tempo libero.', 'Ho cominciato a dipingere emulando mio papà, pittore professionista. Da lui ho ereditato la passione per i paesaggi e i colori della natura. Sono un esperto della pittura ad olio. Per dipingere preferisco essere all&#039;aria aperta, per poter cogliere la forza emotiva dei colori al variare delle ore e del clima.'),
(6, 'mati_bonomo', '$2b$12$SIXkPNTc4gR5goGXPSICJecLYA50Tr9ypCbfoj7z4iOM/2YA/MnPy', 'Matilda', 'Bonomo', 0, '../uploads/users/6.jpg', '1998-10-01', 'Padova', 'Dalla Romania con furore! Studentessa al secondo anno di Informatica. Gioco a pallavolo e pratico nuoto. Sogno di diventare una sviluppatrice web. Quando non studio, adoro disegnare mentre ascolto musica. Spegni e riaccendi come stile di vita.', 'Non ho mai seguito alcun corso o scuola per imparare a disegnare, ma questo non mi vieta di mostrare agli altri i miei disegni. Mi piace ritrarre edifici avvolti dall&#039;oscurità, giocando con luci e ombre utilizzando colori ad acqua.'),
(7, 'colu_nisc84', '$2b$12$tYT46l.lE.tjINjbkCg76eRiK8XsrbWLOHQ1mr2YfWpSDC2uX3nr2', 'Coluccio', 'Niscoromni', 0, '../uploads/users/7.jpg', '1997-03-05', 'Padova', 'Lingue, letterature e mediazione culturale. Amante della vita e di ciò che ne deriva. Parlo sei lingue diverse, ma rimango umile. Il mio sogno è visitare la Cina. Non so disegnare, ma spaccio i miei scarabocchi per arte.', 'Non sono di certo un artista, ma la voglia di mettersi in gioco non mi manca. Qualche scarabocchio carino ogni tanto riesco a farlo, e quindi voglio mostrarlo anche agli altri. Tutte le mie opere cercano di essere ironiche e benauguranti, nella speranza di strappare un sorriso a qualcuno.'),
(8, 'erme.zana71', '$2b$12$4f1h14KTE8q0U9cVdoQD0e0yj2oZEhZnTuS2YUOHbFvi92g8Bgcne', 'Ermes', 'Zanazzo', 0, '../uploads/users/8.jpg', '1997-11-08', 'Venzone', 'Studio Storia dell&#039;arte alla Unipd. Quando non c&#039;è Alberto Angela in televisione, dipingo per divertimento. Suono la chitarra e la tastiera. Amante dell&#x27;arte in tutti i suoi aspetti. Non chiedere la mia squadra del cuore, ma il mio pittore del cuore!', 'Ho studiato in un liceo artistico, raffinando le mie capacità artistiche ed espandendo il mio interesse per l&#039;arte tutta. Solitamente ritraggo uomini e donne, esplorando le emozioni umane tramite l&#039;uso delle ombre e i contrasti dei colori. Ogni mia opera cerca di dare una riflessione psicologica dell&#x27;essere umano.'),
(9, 'erika.parr98', '$2b$12$9agpP0IR3m360Vf.EbmcI.0yC10140B.wTZ9CfOKB4CHyhRsn7FKK', 'Erika', 'Parri', 0, '../uploads/users/9.jpg', '2004-02-07', 'Cavargna', 'Studentessa al primo anno di Scienze dello spettacolo e produzione multimediale. Fotografa e pittrice a tempo perso. Mi piacciono le passeggiate all&#039;aria aperta e le escursioni in montagna. Anche quando studio, non posso non ascoltare musica. Faccio yoga e vado in palestra per tenermi in forma.', 'Prima di iscrivermi all&#039;università ho frequentato un liceo artistico, dove sono migliorata molto nel disegno e nella pittura. Ho avuto il piacere di fare delle illustrazioni per riviste e giornali nazionali, con cui mi sono messa alla prova. Quello che disegno maggiormente sono i paesaggi. Mi piace raffigurare lo scorrere del tempo, dando forma al deterioramento naturale delle cose.'),
(10, 'paolett.travagl', '$2b$12$cuIrNq2nE.CGbVgbhYQ/q.ncEBtzkt2V6aN8myo1gsgfc3D8kSUPy', 'Paoletta', 'Travaglio', 0, '../uploads/users/10.jpg', '2004-11-23', 'Barchi Di Asola', 'Frequento il terzo anno di Matematica. Studio e lavoro con i numeri, ma nel tempo libero dipingo per divertirmi. Adoro passeggiare all&#039;aria aperta, a stretto contatto con la natura. Amo gli animali: ho due cani e tre gatti.', 'Non ho mai frequentato corsi di pittura, né partecipato a concorsi. Tutto quello che so fare con il pennello l&#039;ho imparato da me. Nelle mie opere mi piace raffigurare il mare, cercando di catturare le emozioni che solo esso è in grado di farci provare.'),
(11, 'mic_pizza', '$2b$12$l9ClmTrGHA0tVl1RauJ.IOZzT2SVB1r14zfh/ZaZUAk2uin7ipbFO', 'Michela', 'Pizzamano', 0, '../uploads/users/11.jpg', '2001-09-02', 'Padova', 'Sono una studentessa di Medicina veterinaria alla UniPD. Gli animali sono tutta la mia vita. Mi piace portare a spasso i miei tre cani, e fare lunghe passeggiate all&#039;aria aperta. Quando non studio, passo molto tempo con i miei amici a quattro zampe e a dipingere. Amante del buon cibo e del vino.', 'Ho imparato a dipingere grazie a un corso di disegno e pittura. Da allora, mi esercito tutti i giorni. Mi piace raffigurare la natura, usando i colori per trasmettere la pace e la calma che solo una giornata di sole ti può dare. Spesso dipingo all&#039;aria aperta, osservando il paesaggio che voglio rappresentare.'),
(12, 'cost_faggian', '$2b$12$xV/ElLScpR8TJBhmzgSqdO0gF/TCunIAsM7LZXwGwt2alB2gC5w/i', 'Costanzo', 'Faggiani', 0, '../uploads/users/12.jpg', '2000-08-29', 'Burana', 'Studio Scienze del paesaggio a Padova. Sono un fumettista provetto, con la passione per la fotografia e l&#039;amore per la natura. Mi piace avventurarmi nei campi, con la macchina fotografica pronta a catturare la bellezza della natura. Quando non faccio queste cose, potete trovarmi in oratorio a giocare a calcetto.', 'Faccio disegni e illustrazioni per le riviste della mia parrocchia. Ho frequentato vari corsi di fumetti e illustrazioni a mano libera. Il mio marchio di fabbrica sono le vignette umoristiche, per questo ho deciso di portare il mio talento in questa esperienza. Strappare un sorriso è il mio obiettivo.'),
(13, 'angelina_travagl', '$2b$12$ShXY2/qFEkMp9iH4SSbAbejnLkUSJ9Gy6i5NS.8.lB6wCVeQmHHJi', 'Angelina', 'Travaglio', 0, '../uploads/users/13.jpg', '1997-05-15', 'Padova', 'Medicina veterinaria. Gli animali sono migliori degli uomini. Nuoto e pallavolo per divertirmi. Mi piace ascoltare la musica e guardare serie televisive. Disegno animali per passare il tempo. Il mio sogno è diventare una veterinaria.', 'Non ho particolari esperienze. Da quando sono bambina, mi piace disegnare animali con gli acquerelli, e con il tempo sono diventata brava. Nelle mie opere sono sempre presenti, o in primo piano o in lontananza, degli animali. Cerco di esprimere la maestosità della natura con l&#039;uso sapiente dei colori.'),
(14, 'gabriell-asmundo', '$2b$12$8Ac8DB06Pb84dFXfOt8UCe.YIKhuBCVHD3jXzrNf/n0aXHB1pYtCO', 'Gabriella', 'Asmundo', 0, '../uploads/users/14.jpg', '2000-04-09', 'Padova', 'Studio Pluralismo culturale, mutamento sociale e migrazioni all&#039;università di Padova. Mi piace viaggiare e scoprire nuove culture. Parlo francese, spagnolo, portoghese e tedesco, ma il mio sogno è imparare il russo. Nel tempo libero dipingo e suono l&#039;arpa. Ad un buon caffè non dico mai di no.', 'Ho imparato a disegnare e dipingere grazie a mio fratello. Tutto quello che so fare, me lo ha insegnato lui. Nelle mie opere sono sempre presenti delle persone. Cerco di raffigurarle mentre compiono azioni semplici, in momenti intimi. Il mio obiettivo è raffigurare le emozioni dell&#039;uomo, causate o dal luogo in cui si trova, o dallo stato emotivo del soggetto ritratto.'),
(15, 'adelmo_ruffi57', '$2b$12$iJfetrcEYr.YRzTwmtCSR.CcFVoAJPoknOz0y0J75HIjdvKxnJ.9i', 'Adelmo', 'Ruffini', 0, '../uploads/users/15.jpg', '2004-05-16', 'Padova', 'Fisica alla unipd. Sole, mare e musica i miei punti deboli. Viaggio molto, mi piace visitare le città d&#039;arte. Mi piace andare in giro in bicicletta, pedalando sotto il sole all&#039;aria fresca. Gioco a pallavolo nel tempo libero. Quando capita dipingo.', 'Frequento la scuola di pittura di mia zia, dove sono esposte alcune delle mie opere. Sono specializzato nella rappresentazione degli elementi naturali e delle cittadine. Quando viaggio, prendo sempre una cartolina del paesaggio del posto che visito per poi dipingerlo una volta tornato.'),
(16, 'user', '$2b$12$MZONaw9zSlE0qlxhLQK8v.6hVclryAfkRllERLMZbGIwTVrMG0WKO', 'Margherita', 'Turati', 0, '../uploads/users/16.jpg', '1998-08-17', 'Sezze', 'Studentessa di Lingue, letterature e mediazione culturale presso l&#039;Università degli studi di Padova. Mi piacciono gli anime e i manga. Nel tempo libero, aiuto mia mamma con il suo negozio di fiori. Amante della natura e degli animali.', 'Non sono un artista, né ho fatto alcun corso di disegno o pittura. Dipingo per puro divertimento. Decoro vasi di vetro e ceramica con disegni floreali, per poi venderli nella fioreria di famiglia. Con le mie opere cerco di unire l&#039;eleganza dei fiori con la fragilità del vetro e della porcellana, alla ricerca della fusione tra bellezza e semplicità.');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `ArtshowPrenotations`
--
ALTER TABLE `ArtshowPrenotations`
  ADD PRIMARY KEY (`id_artshow`,`id_artist`),
  ADD KEY `id_artist` (`id_artist`);

--
-- Indici per le tabelle `Artshows`
--
ALTER TABLE `Artshows`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `image` (`image`);

--
-- Indici per le tabelle `ArtworkDetails`
--
ALTER TABLE `ArtworkDetails`
  ADD PRIMARY KEY (`id_artwork`,`image`),
  ADD UNIQUE KEY `image` (`image`);

--
-- Indici per le tabelle `ArtworkLabels`
--
ALTER TABLE `ArtworkLabels`
  ADD PRIMARY KEY (`id_artwork`,`label`),
  ADD KEY `label` (`label`);

--
-- Indici per le tabelle `Artworks`
--
ALTER TABLE `Artworks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `main_image` (`main_image`),
  ADD KEY `id_artist` (`id_artist`);

--
-- Indici per le tabelle `Labels`
--
ALTER TABLE `Labels`
  ADD PRIMARY KEY (`label`);

--
-- Indici per le tabelle `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `image` (`image`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `Artshows`
--
ALTER TABLE `Artshows`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT per la tabella `Artworks`
--
ALTER TABLE `Artworks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT per la tabella `Users`
--
ALTER TABLE `Users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `ArtshowPrenotations`
--
ALTER TABLE `ArtshowPrenotations`
  ADD CONSTRAINT `ArtshowPrenotations_ibfk_1` FOREIGN KEY (`id_artshow`) REFERENCES `Artshows` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ArtshowPrenotations_ibfk_2` FOREIGN KEY (`id_artist`) REFERENCES `Users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `ArtworkDetails`
--
ALTER TABLE `ArtworkDetails`
  ADD CONSTRAINT `ArtworkDetails_ibfk_1` FOREIGN KEY (`id_artwork`) REFERENCES `Artworks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `ArtworkLabels`
--
ALTER TABLE `ArtworkLabels`
  ADD CONSTRAINT `ArtworkLabels_ibfk_1` FOREIGN KEY (`id_artwork`) REFERENCES `Artworks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ArtworkLabels_ibfk_2` FOREIGN KEY (`label`) REFERENCES `Labels` (`label`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `Artworks`
--
ALTER TABLE `Artworks`
  ADD CONSTRAINT `Artworks_ibfk_1` FOREIGN KEY (`id_artist`) REFERENCES `Users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
