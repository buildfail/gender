<?php
namespace Gender;

/**
 * Class Gender ported from pecl/gender 0.6.0
 * @package Gender
 */
class Gender
{
    const IS_FEMALE = 70;
    const IS_MOSTLY_FEMALE = 102;
    const IS_MALE = 77;
    const IS_MOSTLY_MALE = 109;
    const IS_UNISEX_NAME = 63;
    const IS_A_COUPLE = 67;
    const NAME_NOT_FOUND = 32;
    const ERROR_IN_NAME = 69;

    const ANY_COUNTRY = 0;
    const BRITAIN = 1;
    const IRELAND = 2;
    const USA = 3;
    const SPAIN = 4;
    const PORTUGAL = 5;
    const ITALY = 6;
    const MALTA = 7;
    const FRANCE = 8;
    const BELGIUM = 9;
    const LUXEMBOURG = 10;
    const NETHERLANDS = 11;
    const GERMANY = 12;
    const EAST_FRISIA = 13;
    const AUSTRIA = 14;
    const SWISS = 15;
    const ICELAND = 16;
    const DENMARK = 17;
    const NORWAY = 18;
    const SWEDEN = 19;
    const FINLAND = 20;
    const ESTONIA = 21;
    const LATVIA = 22;
    const LITHUANIA = 23;
    const POLAND = 24;
    const CZECH_REP = 25;
    const SLOVAKIA = 26;
    const HUNGARY = 27;
    const ROMANIA = 28;
    const BULGARIA = 29;
    const BOSNIA = 30;
    const CROATIA = 31;
    const KOSOVO = 32;
    const MACEDONIA = 33;
    const MONTENEGRO = 34;
    const SERBIA = 35;
    const SLOVENIA = 36;
    const ALBANIA = 37;
    const GREECE = 38;
    const RUSSIA = 39;
    const BELARUS = 40;
    const MOLDOVA = 41;
    const UKRAINE = 42;
    const ARMENIA = 43;
    const AZERBAIJAN = 44;
    const GEORGIA = 45;
    const KAZAKH_UZBEK = 46;
    const TURKEY = 47;
    const ARABIA = 48;
    const ISRAEL = 49;
    const CHINA = 50;
    const INDIA = 51;
    const JAPAN = 52;
    const KOREA = 53;
    const VIETNAM = 54;
    
    protected const COUNTRY_NAMES = [
        Gender::BRITAIN => ["UK", "Great Britain"],
        Gender::IRELAND => ["IRE", "Ireland"],
        Gender::USA => ["USA", "U.S.A."],
        
        Gender::ITALY => ["I", "Italy"],
        Gender::MALTA => ["M", "Malta"],
        Gender::PORTUGAL => ["P", "Portugal"],
        Gender::SPAIN => ["E", "Spain"],
        Gender::FRANCE => ["F", "France"],
        
        Gender::BELGIUM => ["B", "Belgium"],
        Gender::LUXEMBOURG => ["LUX", "Luxembourg"],
        Gender::NETHERLANDS => ["NL", "the Netherlands"],
        
        Gender::EAST_FRISIA => ["FRI", "East Frisia"],
        Gender::GERMANY => ["D", "Germany"],
        Gender::AUSTRIA => ["A", "Austria"],
        Gender::SWISS => ["CH", "Swiss"],
        
        Gender::ICELAND => ["ICE", "Iceland"],
        Gender::DENMARK => ["DK", "Denmark"],
        Gender::NORWAY => ["N", "Norway"],
        Gender::SWEDEN => ["S", "Sweden"],
        Gender::FINLAND => ["FIN", "Finland"],
        
        Gender::ESTONIA => ["EST", "Estonia"],
        Gender::LATVIA => ["LTV", "Latvia"],
        Gender::LITHUANIA => ["LIT", "Lithuania"],
        
        Gender::POLAND => ["PL", "Poland"],
        Gender::CZECH_REP => ["CZ", "Czech Republic"],
        Gender::SLOVAKIA => ["SK", "Slovakia"],
        Gender::HUNGARY => ["H", "Hungary"],
        Gender::ROMANIA => ["RO", "Romania"],
        Gender::BULGARIA => ["BG", "Bulgaria"],
        
        Gender::BOSNIA => ["BIH","Bosnia and Herzegovina"],
        Gender::CROATIA => ["CRO", "Croatia"],
        Gender::KOSOVO => ["KOS", "Kosovo"],
        Gender::MACEDONIA => ["MK", "Macedonia"],
        Gender::MONTENEGRO => ["MON", "Montenegro"],
        Gender::SERBIA => ["SER", "Serbia"],
        Gender::SLOVENIA => ["SLO", "Slovenia"],
        Gender::ALBANIA => ["AL", "Albania"],
        Gender::GREECE => ["GR", "Greece"],
        
        Gender::RUSSIA => ["RUS", "Russia"],
        Gender::BELARUS => ["BY", "Belarus"],
        Gender::MOLDOVA => ["MOL", "Moldova"],
        Gender::UKRAINE => ["UKR", "Ukraine"],
        Gender::ARMENIA => ["ARM", "Armenia"],
        Gender::AZERBAIJAN => ["AZE", "Azerbaijan"],
        Gender::GEORGIA => ["GEO", "Georgia"],
        Gender::KAZAKH_UZBEK => ["KAZ", "Kazakhstan/Uzbekistan"],
        
        Gender::TURKEY => ["TR", "Turkey"],
        Gender::ARABIA => ["AR", "Arabia/Persia"],
        Gender::ISRAEL => ["ISR", "Israel"],
        Gender::CHINA => ["CHN", "China"],
        Gender::INDIA => ["IND", "India/Sri Lanka"],
        Gender::JAPAN => ["JAP", "Japan"],
        Gender::KOREA => ["KOR", "Korea"],
        Gender::VIETNAM => ["VN", "Vietnam"],
    ];

    protected const CHECK_STRING = "# DO NOT CHANGE:   FILE-FORMAT DEFINITION-DATE = 2008-11-16 ";

    protected const DATA_NAME_POS =     3;
    protected const DATA_NAME_LENGTH =  26;
    protected const MAX_LINE_SIZE =     100;

    /**
     * @var resource
     */
    private $nameDataFile;

    /**
     * Create a Gender object optionally connecting to an external name dictionary. When no external database was given, compiled in data will be used.
     * @param null|string $dsn DSN to open.
     * @throws \InvalidArgumentException When $dsn is invalid.
     */
    public function __construct(?string $dsn = null)
    {
        if ($dsn === null) {
            $dsn = __DIR__ . '/../data/nam_dict.txt';
        }

        if (!$this->connect($dsn)) {
            throw new \RuntimeException("Unable to access data file: $dsn");
        }
    }

    /**
     * Connect to an external name dictionary.
     * @param string $dsn DSN to open.
     * @return bool Boolean as success or failure.
     * @throws \InvalidArgumentException When $dsn is invalid.
     */
    public function connect(string $dsn): bool
    {
        if ($this->nameDataFile !== null) {
            fclose($this->nameDataFile);
        }

        $this->nameDataFile = fopen($dsn, 'r');

        return $this->nameDataFile !== false;
    }

    /**
     * Returns the textual representation of a country from a Gender class constant.
     * @param int $country A country ID specified by a Gender\Gender class constant.
     * @return array Returns an array with the short and full names of the country.
     * @throws \InvalidArgumentException When $country is unknown.
     */
    public function country(int $country): array
    {
        if (!isset(self::COUNTRY_NAMES[$country])) {
            throw new \InvalidArgumentException("Unknown country: $country");
        }
        list($shortName, $longName) = self::COUNTRY_NAMES[$country];

        return [
            "country_short" => $shortName,
            "country" => $longName
        ];
    }

    /**
     * Get the gender of the name in a particular country.
     * @param string $name Name to check.
     * @param int $country Country id identified by Gender class constant.
     * @return int Returns gender of the name.
     */
    public function get(string $name, int $country = Gender::ANY_COUNTRY): int
    {
        //Lookup $name, if found return result
        //Split on space, hyphen or dot, and lookup each part (including dots for abbreviations), combining results
    }

    protected function searchName(string $name, int $country): int
    {
        //If empty return UNISEX
        //if at least 2 chars and ends in dot set mode to abbr.
        // do a bin search on column 3, assuming it's 26 chars long
        // if result is <0 and == 10 do internal error, if just < 0 do name not found
        // seek to the pos

        //Enter restart loop
        //TODO: Track through the rest of internal_search (probably can ignore anything other than &searchGender,
        //      and share the logic as we get to the other methods
    }

    /**
     * Find the first occurrence of the $name in the data source.
     * @param string $name
     * @param bool $getMatchOrNextHigher
     * @return int -10 on internal error, -1 on not found, or the position of the start of the line in the data source if found.
     */
    protected function binarySearch(string $name, bool $getMatchOrNextHigher = false): int
    {
        $offset = Gender::DATA_NAME_POS;
        $length = Gender::DATA_NAME_LENGTH;
        //find line size
            //Seek to begining return -10 on failure
            //Read the first line
            //Check that it is exactly the check line, return -10 otherwise
            //set line size to stream position
        //Find record cound
            //Seek to end return -10 on failure
            //set record count to stream position+1 /line size

        //Find
            //Start Position 1 at the first record, and position2 at the end
            //Set current position to the middle record
            //read the line of the current position (seek then read)
            //Read the name from offset up to length, unless the line starts with #
            //Using weird built in str cmp check if they match ignoring separators
            //If they do, and the position = p1 break, if p != p1, set position 2 to the current position
            //If the first character that does not match has a lower value set p2 = p-1
            //if the first charcter that does not match has a higher value set p1 = p+1 and set p = p+1
            //repeat while p1 <= p2

        //If none were found, check if we should return whatever was after the last line we looked at, so long as the last thing we looked at was higher than our name

        //if found something (either through search or next higher) return the start of the line as an int (p * line length)

        //If none were found return -1
    }

    protected function strcmp(string $name, string $name2, bool $shouldCompareAbbreviations, $shouldIgnoreSeparators): int
    {
        //If should cmp abr. check if $name ends in dot, and see if $name2 starts with $name up to dot
        //convert all chars in $name and $name2 using substitutions called for in algo via sortchar and sortchar2
        //then use strcmp
    }

    /**
     * Check whether the name0 is a nick of the name1.
     * @param string $name0 Name to check.
     * @param string $name1 Name to check.
     * @param int $country Country id identified by Gender class constant. If omitted ANY_COUNTRY is used.
     * @return bool
     */
    public function isNick(string $name0, string $name1, int $country = Gender::ANY_COUNTRY): bool
    {
    }

    /**
     * Get similar names for the given name and country.
     * @param string $name Name to check.
     * @param int $country Country id identified by Gender class constant. If ommited ANY_COUNTRY is used.
     * @return string[]
     */
    public function similarNames(string $name, ?int $country = Gender::ANY_COUNTRY): array
    {
        //This will be a very long method
    }
}
