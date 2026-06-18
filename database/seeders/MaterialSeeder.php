<?php

namespace Database\Seeders;

use App\Models\Material;
use Illuminate\Database\Seeder;

class MaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $materialData = [
            ['name' => 'Bouten M6', 'category_id' => 1, 'image_path' => 'bouten-m6.webp', 'description' => 'Metrische zeskantbout M6, geschikt voor lichte constructies en bevestigingen.', 'type' => 'ALWAYS'],
            ['name' => 'Bouten M8', 'category_id' => 1, 'image_path' => 'bouten-m8.jpg', 'description' => 'Metrische zeskantbout M8, veelgebruikt in mechanische en constructieve toepassingen.', 'type' => 'ALWAYS'],
            ['name' => 'Bouten M10', 'category_id' => 1, 'image_path' => 'bouten-m10.jpg', 'description' => 'Metrische zeskantbout M10, voor middelzware constructies en machinebouw.', 'type' => 'ALWAYS'],
            ['name' => 'Bouten M12', 'category_id' => 1, 'image_path' => 'bouten-m12.jpg', 'description' => 'Metrische zeskantbout M12, geschikt voor zware industriële bevestigingen.', 'type' => 'NORMAL'],
            ['name' => 'Bouten M16', 'category_id' => 1, 'image_path' => 'bouten-m16.jpg', 'description' => 'Metrische zeskantbout M16, voor zware belastingen in staalconstructies.', 'type' => 'NORMAL'],
            ['name' => 'Bouten inox A2', 'category_id' => 1, 'image_path' => 'bouten-inox-a2.jpg', 'description' => 'Roestvrijstalen bout A2 (AISI 304), corrosiebestendig voor binnen- en buitentoepassingen.', 'type' => 'HUMID'],
            ['name' => 'Bouten inox A4', 'category_id' => 1, 'image_path' => 'bouten-inox-a4.webp', 'description' => 'Roestvrijstalen bout A4 (AISI 316), verhoogde corrosiebestendigheid voor zeewatermilieus.', 'type' => 'HUMID'],
            ['name' => 'Bouten verzinkt', 'category_id' => 1, 'image_path' => 'boutenverzinkt.webp', 'description' => 'Elektrolytisch verzinkte bout, beschermd tegen roest voor gebruik in vochtige omgevingen.', 'type' => 'HUMID'],
            ['name' => 'Zeskantmoeren', 'category_id' => 1, 'image_path' => 'zeskantmoeren.jpg', 'description' => 'Standaard zeskantmoer voor gebruik met metrische bouten en draadstangen.', 'type' => 'NORMAL'],
            ['name' => 'Borgmoeren', 'category_id' => 1, 'image_path' => 'borgmoeren.jpg', 'description' => 'Zelfborgende moer met nylonring, voorkomt losdraaien door trillingen.', 'type' => 'NORMAL'],
            ['name' => 'Flensmoeren', 'category_id' => 1, 'image_path' => 'flensmoeren.jpg', 'description' => 'Moer met geïntegreerde flens die de druk verdeelt over een groter oppervlak.', 'type' => 'NORMAL'],
            ['name' => 'Sluitringen', 'category_id' => 1, 'image_path' => 'sluitringen.jpg', 'description' => 'Vlakke sluitring voor betere drukopname en bescherming van het werkstukoppervlak.', 'type' => 'NORMAL'],
            ['name' => 'Veerringen', 'category_id' => 1, 'image_path' => 'veerringen.jpg', 'description' => 'Borgring met veerende werking, biedt weerstand tegen losdraaien door trillingen.', 'type' => 'NORMAL'],
            ['name' => 'Tandringen', 'category_id' => 1, 'image_path' => 'tandringen.jpg', 'description' => 'Getande borgring die zich vastzet in het materiaal voor maximale borging.', 'type' => 'NORMAL'],
            ['name' => 'Ankerbouten', 'category_id' => 1, 'image_path' => 'ankerbouten.jpg', 'description' => 'Verankeringsbouten voor bevestiging van constructies in beton of metselwerk.', 'type' => 'NORMAL'],
            ['name' => 'Chemische ankers (Hilti HIT)', 'category_id' => 1, 'image_path' => 'chemische-ankers.jpg', 'description' => 'Chemisch verankeringssysteem van Hilti voor hoogbelaste bevestigingen in beton.', 'type' => 'NORMAL'],
            ['name' => 'Keilbouten', 'category_id' => 1, 'image_path' => 'keilbouten.webp', 'description' => 'Expansiebout voor mechanische verankering in beton en steen.', 'type' => 'NORMAL'],
            ['name' => 'Draadstang M6', 'category_id' => 1, 'image_path' => 'raadstang-m6.jpg', 'description' => 'Volledig gedraaide stang M6, op maat te knippen voor diverse bevestigingsoplossingen.', 'type' => 'NORMAL'],
            ['name' => 'Draadstang M8', 'category_id' => 1, 'image_path' => 'draadstang-m8.jpg', 'description' => 'Volledig gedraaide stang M8 voor gebruik in lichte tot middelzware constructies.', 'type' => 'NORMAL'],
            ['name' => 'Draadstang M10', 'category_id' => 1, 'image_path' => 'draadstang-m10.jpg', 'description' => 'Volledig gedraaide stang M10 voor middelzware constructie- en installatietoepassingen.', 'type' => 'NORMAL'],
            ['name' => 'Draadstang M12', 'category_id' => 1, 'image_path' => 'draadstang-m12.jpg', 'description' => 'Volledig gedraaide stang M12 voor zware constructies en leidingophanging.', 'type' => 'NORMAL'],
            ['name' => 'Draadstang M16', 'category_id' => 1, 'image_path' => 'draadstang-m16.jpg', 'description' => 'Volledig gedraaide stang M16 voor zware industriële verankeringstoepassingen.', 'type' => 'NORMAL'],
            ['name' => 'Inslagmoeren', 'category_id' => 1, 'image_path' => 'inslagmoeren.avif', 'description' => 'Blindmoer die in hout of kunststof wordt ingeslagen voor verborgen bevestigingen.', 'type' => 'NORMAL'],
            ['name' => 'Tapbouten', 'category_id' => 1, 'image_path' => 'tapbouten.jpg', 'description' => 'Bout met fijn draad voor gebruik in dunne materialen of metaalplaten.', 'type' => 'NORMAL'],
            ['name' => 'Zeskantkopbouten', 'category_id' => 1, 'image_path' => 'zeskantkopbouten.jpg', 'description' => 'Standaard zeskantkopbout voor algemene constructie- en montagetoepassingen.', 'type' => 'NORMAL'],
            ['name' => 'Inbusbouten', 'category_id' => 1, 'image_path' => 'inbusbouten.jpg', 'description' => 'Cilinderkopbout met inbus (zeskant) aandrijving, voor smalle ruimtes.', 'type' => 'NORMAL'],
            ['name' => 'Torxschroeven', 'category_id' => 1, 'image_path' => 'torxschroeven.jpg', 'description' => 'Schroef met Torx-aandrijving, biedt meer aandrijfkracht en minder wegglijden.', 'type' => 'NORMAL'],
            ['name' => 'Kruiskopschroeven', 'category_id' => 1, 'image_path' => 'kruiskopschroeven.webp', 'description' => 'Schroef met Philipps kruiskop, universeel toepasbaar in hout en metaal.', 'type' => 'NORMAL'],
            ['name' => 'Zelftappende vijzen', 'category_id' => 1, 'image_path' => 'zelftappende-vijzen.jpg', 'description' => 'Schroef die eigen draad snijdt in dunne metaalplaten zonder voorboren.', 'type' => 'NORMAL'],
            ['name' => 'Parkervijzen', 'category_id' => 1, 'image_path' => 'parkervijzen.jpg', 'description' => 'Zelftappende plaatschroef voor bevestiging in dunne staal- en aluminiumpanelen.', 'type' => 'NORMAL'],
            ['name' => 'Spaanplaatschroeven', 'category_id' => 1, 'image_path' => 'spaanplaatschroeven.jpg', 'description' => 'Houtschroef met grof draad, geoptimaliseerd voor spaanplaat en MDF.', 'type' => 'NORMAL'],
            ['name' => 'Slangenklemmen', 'category_id' => 1, 'image_path' => 'slangenklemmen.jpg', 'description' => 'Klem voor het afdichten en bevestigen van slangen op aansluitingen.', 'type' => 'HUMID'],

// Categorie 2 - Persoonlijke beschermingsmiddelen
            ['name' => 'Veiligheidshelm (met kinband)', 'category_id' => 2, 'image_path' => 'veiligheidshelm.jpg', 'description' => 'Harde helm met kinband voor hoofdbescherming op de werf conform EN 397.', 'type' => 'ALWAYS'],
            ['name' => 'Oordoppen / gehoorkappen', 'category_id' => 2, 'image_path' => 'oordoppen-gehoorkappen.jpg', 'description' => 'Gehoorbescherming tegen lawaai, beschikbaar als oordop of kap conform EN 352.', 'type' => 'ALWAYS'],
            ['name' => 'Veiligheidsbril / gelaatsscherm', 'category_id' => 2, 'image_path' => 'veiligheidsbril-gelaatsscherm.webp', 'description' => 'Oogbescherming tegen spatten, stof en vliegende deeltjes conform EN 166.', 'type' => 'ALWAYS'],
            ['name' => 'Stofmaskers (FFP2, FFP3)', 'category_id' => 2, 'image_path' => 'stofmaskers.webp', 'description' => 'Filterend halfmasker FFP2/FFP3 voor bescherming tegen fijn stof en aerosolen.', 'type' => 'HOT'],
            ['name' => 'Werkhandschoenen (snijvast, chemisch resistent, elektrisch geïsoleerd)', 'category_id' => 2, 'image_path' => 'werkhandschoenen.jpg', 'description' => 'Beschermende handschoenen beschikbaar in snijvaste, chemisch resistente of geïsoleerde uitvoering.', 'type' => 'ALWAYS'],
            ['name' => 'Veiligheidsschoenen (S3, antistatisch, stalen tip)', 'category_id' => 2, 'image_path' => 'veiligheidsschoenen.jpg', 'description' => 'S3-veiligheidsschoen met stalen neus, antistatisch en waterdicht conform EN ISO 20345.', 'type' => 'ALWAYS'],
            ['name' => 'Werklaarzen (PVC, nitril, met stalen zool)', 'category_id' => 2, 'image_path' => 'werklaarzen.jpg', 'description' => 'Werklaars in PVC of nitril met stalen zool voor natte en gevaarlijke werkomgevingen.', 'type' => 'RAINY'],
            ['name' => 'Regenkledij (jassen, broeken, capes)', 'category_id' => 2, 'image_path' => 'regenkledij.jpg', 'description' => 'Waterdichte werkkleding: jassen, broeken en capes voor weersomstandigheden.', 'type' => 'RAINY'],
            ['name' => 'Fluovesten / signalisatiekledij (EN ISO 20471)', 'category_id' => 2, 'image_path' => 'Fluovesten-signalisatiekledij.jpg', 'description' => 'Hoog-zichtbare signalisatiekledij conform EN ISO 20471 voor gevaarlijke werkomgevingen.', 'type' => 'ALWAYS'],
            ['name' => 'Overall (brandvertragend, antistatisch, waterafstotend)', 'category_id' => 2, 'image_path' => 'overall.jpg', 'description' => 'Werkoverall in brandvertragend, antistatisch of waterafstotend materiaal naar keuze.', 'type' => 'ALWAYS'],
            ['name' => 'Valharnas en lijn', 'category_id' => 2, 'image_path' => 'valharnas-en-lijn.jpg', 'description' => 'Volledig valharnas met veiligheidslijn voor werken op hoogte conform EN 361.', 'type' => 'ALWAYS'],
            ['name' => 'Gasdetectiemeter (O₂, CH₄, H₂S, CO)', 'category_id' => 2, 'image_path' => 'gasdetectiemeter.jpg', 'description' => 'Draagbare multigas-detector voor meting van O₂, methaan, H₂S en CO.', 'type' => 'ALWAYS'],
            ['name' => 'Handontsmetting / EHBO-kit', 'category_id' => 2, 'image_path' => 'handontsmetting-EHBO-kit.avif', 'description' => 'Eerste hulpset met handontsmetting, verbandmiddelen en noodmedicatie voor op de werf.', 'type' => 'ALWAYS'],
            ['name' => 'Klim- en valbeveiligingsmateriaal (harnas, lifeline, karabijnhaken)', 'category_id' => 2, 'image_path' => 'klim-en-valbeveiligingsmateriaal.jpg', 'description' => 'Volledig klimset met harnas, lifeline en karabijnhaken voor veilig werken op hoogte.', 'type' => 'ALWAYS'],

// Categorie 3 - Gereedschappen
            ['name' => 'Dopsleutelsets (metrisch en inch)', 'category_id' => 3, 'image_path' => 'dopsleutelsets.jpg', 'description' => 'Complete dopsleutelset in metrische en inch-maten voor algemeen onderhoud.', 'type' => 'NORMAL'],
            ['name' => 'Ringsleutels, steeksleutels', 'category_id' => 3, 'image_path' => 'ringsleutels-steeksleutels.png', 'description' => 'Ring- en steeksleutelset voor het aandraaien en losdraaien van bouten en moeren.', 'type' => 'NORMAL'],
            ['name' => 'Momentsleutels', 'category_id' => 3, 'image_path' => 'momentsleutels.jpg', 'description' => 'Instelbare momentsleutel voor het aandraaien op exact voorgeschreven aandraaimoment.', 'type' => 'NORMAL'],
            ['name' => 'Inbussleutels (los en in set)', 'category_id' => 3, 'image_path' => 'inbussleutels.jpg', 'description' => 'Inbussleutels (zeskant) beschikbaar los of als set voor inbusbouten.', 'type' => 'NORMAL'],
            ['name' => 'Schroevendraaiers (plat, kruiskop, Torx, geïsoleerd)', 'category_id' => 3, 'image_path' => 'schroevendraaiers.jpg', 'description' => 'Schroevendraaiers in diverse uitvoeringen: plat, kruiskop, Torx en geïsoleerd tot 1000V.', 'type' => 'NORMAL'],
            ['name' => 'Tangen (combinatie, waterpomptang, kniptang, punttang)', 'category_id' => 3, 'image_path' => 'tangen.jpg', 'description' => 'Tangenset met combinatietang, waterpomptang, kniptang en punttang.', 'type' => 'NORMAL'],
            ['name' => 'Krimptang / kabelschoentang', 'category_id' => 3, 'image_path' => 'krimptang -kabelschoentang.jpg', 'description' => 'Tang voor het crimpen van kabelschoenen en verbindingsbuizen op elektrische kabels.', 'type' => 'NORMAL'],
            ['name' => 'Kabelstripper', 'category_id' => 3, 'image_path' => 'kabelstripper.jpg', 'description' => 'Gereedschap voor het snel en precies strippen van kabelisolatie.', 'type' => 'NORMAL'],
            ['name' => 'Hamer, kunststofhamer, moker', 'category_id' => 3, 'image_path' => 'hamer-kunststofhamer-moker.jpg', 'description' => 'Hamers in diverse uitvoeringen: staal, kunststof en moker voor zwaar slagwerk.', 'type' => 'NORMAL'],
            ['name' => 'Breekijzer', 'category_id' => 3, 'image_path' => 'breekijzer.webp', 'description' => 'Stalen breekijzer voor het opheffen, losbreken en demonteren van constructiedelen.', 'type' => 'NORMAL'],
            ['name' => 'Slijpmachine (haakse slijper)', 'category_id' => 3, 'image_path' => 'slijpmachine.jpg', 'description' => 'Haakse slijper voor het slijpen, snijden en ontroesten van metalen.', 'type' => 'NORMAL'],
            ['name' => 'Accuboormachine / klopboormachine', 'category_id' => 3, 'image_path' => 'accuboormachine -klopboormachine.jpg', 'description' => 'Accu- of klopboormachine voor boren in hout, metaal en beton.', 'type' => 'NORMAL'],
            ['name' => 'Schroefmachine', 'category_id' => 3, 'image_path' => 'schroefmachine.webp', 'description' => 'Elektrische schroefmachine voor snel en efficiënt plaatsen van schroeven.', 'type' => 'NORMAL'],
            ['name' => 'Slagmoersleutel (pneumatisch of accu)', 'category_id' => 3, 'image_path' => 'slagmoersleutel.jpg', 'description' => 'Pneumatische of accugestuurde slagmoersleutel voor snel aan- en afdraaien van bouten.', 'type' => 'NORMAL'],
            ['name' => 'Waterpas / laserwaterpas', 'category_id' => 3, 'image_path' => 'waterpas-laserwaterpas.webp', 'description' => 'Waterpas of lasernivelleerder voor het nauwkeurig horizontaal en verticaal uitlijnen.', 'type' => 'NORMAL'],
            ['name' => 'Meetlint, rolmeter', 'category_id' => 3, 'image_path' => 'meetlint-rolmeter.jpg', 'description' => 'Rolmeter of meetlint voor het opmeten van afstanden op de werf.', 'type' => 'NORMAL'],
            ['name' => 'Spanningstester / multimeter', 'category_id' => 3, 'image_path' => 'spanningstester-multimeter.jpg', 'description' => 'Elektrisch meettoestel voor het meten van spanning, stroom en weerstand.', 'type' => 'NORMAL'],
            ['name' => 'Laskist en lasmateriaal (indien van toepassing)', 'category_id' => 3, 'image_path' => 'laskist-lasmateriaal.jpg', 'description' => 'Lasapparatuur en bijbehorend materiaal voor MIG/MAG-, TIG- en elektrodelassen.', 'type' => 'NORMAL'],

// Categorie 4 - Onderhoud & Dichtingen
            ['name' => 'Smeervet (foodgrade, EP2, lithium)', 'category_id' => 4, 'image_path' => 'smeervet.png', 'description' => 'Smeermiddel in foodgrade, EP2 of lithiumbasis voor lagering en mechanische onderdelen.', 'type' => 'HOT'],
            ['name' => 'O-ringen (div. maten en types)', 'category_id' => 4, 'image_path' => 'o-ringen.jpeg', 'description' => 'Rubberen afdichtringen in diverse maten en materialen voor vloeistof- en gasdichting.', 'type' => 'HUMID'],
            ['name' => 'Pakkingen (papier, rubber, EPDM)', 'category_id' => 4, 'image_path' => 'pakkingen.jpg', 'description' => 'Vlakke afdichtpakking in papier, rubber of EPDM voor flenzen en verbindingen.', 'type' => 'HUMID'],
            ['name' => 'PTFE tape / Loctite', 'category_id' => 4, 'image_path' => 'ptfe-tape-loctite.jpg', 'description' => 'PTFE afdichtingstape of Loctite draadborging voor lekvrije schroefdraadverbindingen.', 'type' => 'HUMID'],
            ['name' => 'Slangen (PVC, PE, persslangen)', 'category_id' => 4, 'image_path' => 'slangen.jpg', 'description' => 'Flexibele slangen in PVC, PE of persuitvoering voor vloeistof- en luchtgeleiding.', 'type' => 'RAINY'],
            ['name' => 'PVC-fittingen, bochten, T-stukken', 'category_id' => 4, 'image_path' => 'pvc-fittingen-bochten-T-stukken.jpg', 'description' => 'PVC leidingfittingen: bochten, T-stukken en koppelingen voor drukleidingen.', 'type' => 'RAINY'],
            ['name' => 'Koppelingen (Geka, Gardena, Camlock)', 'category_id' => 4, 'image_path' => 'koppelingen.jpg', 'description' => 'Snelkoppelingen van het type Geka, Gardena of Camlock voor slang- en leidingaansluitingen.', 'type' => 'RAINY'],
            ['name' => 'V-snaren / kettingen', 'category_id' => 4, 'image_path' => 'v-snaren-kettingen.jpg', 'description' => 'Aandrijfriemen en kettingen voor transmissies in pompen en machines.', 'type' => 'NORMAL'],
            ['name' => 'Kabels en wartels (M16–M32)', 'category_id' => 4, 'image_path' => 'kabels-en-wartels.webp', 'description' => 'Kabeldoorvoerwartels M16 tot M32 voor waterdichte kabelinvoer in kasten en motoren.', 'type' => 'HUMID'],
            ['name' => 'Aansluitdozen', 'category_id' => 4, 'image_path' => 'aansluitdozen.jpeg', 'description' => 'Elektrische aansluitdoos voor het verbinden en beschermen van kabelverbindingen.', 'type' => 'NORMAL'],
            ['name' => 'Leidingsystemen (druk/afvoer)', 'category_id' => 4, 'image_path' => 'leidingsystemen.jpg', 'description' => 'Buizensystemen voor druk- en afvoerleidingen in riool- en waterbehandelingsinstallaties.', 'type' => 'RAINY'],
            ['name' => 'Pneumatische koppelingen', 'category_id' => 4, 'image_path' => 'pneumatische-koppelingen.jpg', 'description' => 'Snelkoppelingen voor persluchtleidingen in pneumatische installaties.', 'type' => 'NORMAL'],
            ['name' => 'Trillingsdempers', 'category_id' => 4, 'image_path' => 'trillingsdempers.webp', 'description' => 'Rubberen trillingsdempers voor het isoleren van trillingen in pompen en motoren.', 'type' => 'NORMAL'],

// Categorie 5 - Riolering & Inspectie
            ['name' => 'Putdekselhaak / mangatopener', 'category_id' => 5, 'image_path' => 'putdekselhaak-mangatopener.jpg', 'description' => 'Haak of hefgereedschap voor het veilig openen van putdeksels en mangaten.', 'type' => 'ALWAYS'],
            ['name' => 'Rioolcamera / inspectiecamera', 'category_id' => 5, 'image_path' => 'rioolcamera-inspectiecamera.jpg', 'description' => 'Camera voor visuele inspectie van riool- en leidingwerk van binnenuit.', 'type' => 'NORMAL'],
            ['name' => 'Gasdetectietoestellen (H₂S, CO, O₂)', 'category_id' => 5, 'image_path' => 'gasdetectietoestellen.jpg', 'description' => 'Draagbaar gasdetectietoestel voor meting van H₂S, CO en zuurstofgehalte in besloten ruimten.', 'type' => 'ALWAYS'],
            ['name' => 'Ontstoppingsveer / hogedrukreiniger', 'category_id' => 5, 'image_path' => 'ontstoppingsveer-hogedrukreiniger.jpg', 'description' => 'Mechanische ontstoppingsveer of hogedrukreiniger voor het vrijmaken van verstopte leidingen.', 'type' => 'RAINY'],
            ['name' => 'Slangenwagens', 'category_id' => 5, 'image_path' => 'slangenwagens.jpg', 'description' => 'Rijdende slangenwagen voor het ordelijk opbergen en afwikkelen van lange slangen.', 'type' => 'RAINY'],
            ['name' => 'Dompelpompen', 'category_id' => 5, 'image_path' => 'dompelpompen.jpg', 'description' => 'Elektrische dompelpomp voor het afpompen van water uit putten, kelders en riolering.', 'type' => 'RAINY'],
            ['name' => 'Rioolstoppen', 'category_id' => 5, 'image_path' => 'rioolstoppen.webp', 'description' => 'Opblaasbare of mechanische rioolstop voor het tijdelijk afdichten van leidingen.', 'type' => 'RISK_DAY'],
            ['name' => 'Vlotterschakelaars', 'category_id' => 5, 'image_path' => 'vlotterschakelaars.jpg', 'description' => 'Vlotterschakelaar voor automatische niveauregeling van pompen in putten en bassins.', 'type' => 'RISK_DAY'],
            ['name' => 'Niveaumeting (ultrasoon, radar)', 'category_id' => 5, 'image_path' => 'niveaumeting.png', 'description' => 'Ultrasoon of radar niveausensor voor contactloze vloeistofniveaumeting in tanks en putten.', 'type' => 'RISK_DAY'],
            ['name' => 'Staalnamepotten', 'category_id' => 5, 'image_path' => 'staalnamepotten.jpg', 'description' => 'Hersluitbare staalnamepotten voor het nemen van watermonsters voor labo-analyse.', 'type' => 'NORMAL'],
            ['name' => 'Monsternameapparatuur', 'category_id' => 5, 'image_path' => 'monsternameapparatuur.jpg', 'description' => 'Professionele apparatuur voor het automatisch of manueel nemen van watermonsters.', 'type' => 'NORMAL'],

// Categorie 6 - Verbruiksmateriaal & Diversen
            ['name' => 'Tie-wraps', 'category_id' => 6, 'image_path' => 'tie-wraps.jpg', 'description' => 'Nylon kabelbinders in diverse lengtes voor het bundelen en bevestigen van kabels.', 'type' => 'NORMAL'],
            ['name' => 'Kabelschoenen', 'category_id' => 6, 'image_path' => 'kabelschoenen.jpg', 'description' => 'Gecrimpte kabelschoenen in diverse maten voor betrouwbare elektrische aansluitingen.', 'type' => 'NORMAL'],
            ['name' => 'Markeringstape', 'category_id' => 6, 'image_path' => 'markeringstape.webp', 'description' => 'Zelfklevende markeringstape voor het afbakenen van gevaarlijke zones en leidingkleurcodering.', 'type' => 'ALWAYS'],
            ['name' => 'Siliconenkit / lijm', 'category_id' => 6, 'image_path' => 'siliconenkit-lijm.jpg', 'description' => 'Siliconenkit of constructielijm voor afdichting en verbinding van diverse materialen.', 'type' => 'HUMID'],
            ['name' => 'Rags (reinigingsdoekjes)', 'category_id' => 6, 'image_path' => 'rags.jpg', 'description' => 'Absorptiedoeken voor het reinigen van machines, gereedschap en werkoppervlakken.', 'type' => 'ALWAYS'],
            ['name' => "Spray's (WD-40, contactspray, kettingspray)", 'category_id' => 6, 'image_path' => "spray's.jpg", 'description' => 'Smeerspray, contactspray of kettingspray voor onderhoud en conservering van metalen.', 'type' => 'HOT'],
            ['name' => 'Plakband (duct tape, isolatietape)', 'category_id' => 6, 'image_path' => 'plakband.jpg', 'description' => 'Duct tape of isolatietape voor tijdelijke reparaties en elektrische isolatie.', 'type' => 'NORMAL'],
            ['name' => "Batterijen / accu's", 'category_id' => 6, 'image_path' => "batterijen-accu's.jpg", 'description' => "Vervangingsbatterijen en accu's voor meettoestellen, detectoren en draadloos gereedschap.", 'type' => 'ALWAYS'],
            ['name' => 'Reserveonderdelen (motoren, PLC-onderdelen, relais)', 'category_id' => 6, 'image_path' => 'reserveonderdelen.jpg', 'description' => 'Kritische reserveonderdelen zoals motoren, PLC-modules en relais voor snelle interventie.', 'type' => 'NORMAL'],
            ['name' => 'Flessen met perslucht / gas', 'category_id' => 6, 'image_path' => 'flessen-met-perslucht-gas.jpg', 'description' => 'Drukflessen met perslucht of inertgas voor pneumatische tools en beschermde atmosfeer.', 'type' => 'NORMAL'],
        ];

        Material::factory(count($materialData))->createMany($materialData);
    }
}
