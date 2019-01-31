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
    }

    /**
     * Get the gender of the name in a particular country.
     * @param string $name Name to check.
     * @param int $country Country id identified by Gender class constant.
     * @return int Returns gender of the name.
     */
    public function get(string $name, int $country = Gender::ANY_COUNTRY): int
    {
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
    }
}
