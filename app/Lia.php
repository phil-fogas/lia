<?php

declare(strict_types=1);

require_once 'app/Database.php';

//use Database;
/**
 * @author fogas fogasy 
 * @version 1.3
 * @access public
 */

class lia extends Database
{

  /**
   * txt
   */
  private ?string $txt = null;
  /**
   * str
   *
   * @var mixed
   */
  private $str;
  /**
   * monNom
   */
  private string $monNom = 'lia';
  /**
   * exp
   *
   * @var mixed
   */
  protected $exp;

  public function __construct()
  {
    parent::__construct();
    if (session_status() == PHP_SESSION_NONE) {
      session_start();
      //session_id("lia");
    }
  }



  /**
   * jsDec
   *
   * @param  mixed $str
   * @return string
   */
  public function jsDec(string $str): string
  {
    $res = $this->Dec($str);
    return json_encode($res, JSON_THROW_ON_ERROR);
  }

  /**
   * Dec
   *
   * @param  mixed $str
   * @return array
   */
  public function Dec(string $str): array
  {

    $str = preg_replace('/[;:?!]/i', '', $str);
    $str = $this->noAccent($str);


    $this->str = $str;

    $salut = ['bonjour', 'salut', 'salutation', 'bonsoir', 'chalut'];

    foreach ($salut as $value) {

      if (strpos($this->str, $value) !== false) {

        $this->txt = $this->Salutation($value);
      }
    }

    $apel = ["je m appelle", "je me nomme"];
    foreach ($apel as $value) {

      if (strpos($this->str, $value) !== false) {
        $val = preg_replace('#' . $value . ' #', '', $this->str);
        $this->txt = $this->Appelle($val);
      }
    }

    if (strpos($this->str, 'qui est') !== false) {
      $str = preg_replace('#qui est #', '', $this->str);
      $this->txt .= $this->QuiEst($str);
    }

    if (strpos($this->str, 'merci') !== false) {
      $this->txt .= ' de rien ';
    }

    if (strpos($this->str, 'aime tu') !== false) {
      $str = preg_replace('#aime tu #', '', $this->str);
      $this->txt .= $this->AimeTu($str);
    }


    $r = ['la reponse', 'la reponse n°', 'la reponse numero', 'la reponse a la reponse'];

    foreach ($r as $value) {

      if (strpos($this->str, $value) !== false) {
        preg_match('/-?(\d+)/', $this->str, $matches);
        $this->txt = $this->Reponse((int) $matches[0] ?? 0);
      }
    }

    $q = ['la question', 'la question n°', 'la question numero'];

    foreach ($q as $value) {

      if (strpos($this->str, $value) !== false) {
        preg_match('/-?(\d+)/', $this->str, $matches);
        $this->txt = $this->Question((int) $matches[0] ?? 0);
      }
    }

    $h = ['quel heure est il', 'il est quel heure', 'c est quelle heure'];

    foreach ($h as $value) {

      if (strpos($this->str, $value) !== false) {

        $this->txt .= $this->Heure();
      }
    }


    $dj = ['tu es', 'tu est'];
    foreach ($dj as $value) {
      if (strpos($this->str, $value) !== false) {
        $val = preg_replace('#' . $value . ' #', '', $this->str);

        $this->txt .= $this->TuEs($val);
      }
    }


    foreach (['calcul', 'calcul moi'] as $value) {

      if (strpos($this->str, $value) !== false) {
        $val = preg_replace('#' . $value . ' #', '', $this->str);
        $cal = $this->Calcul($val);
        if (!empty($cal)) {
          $this->Expretion('surpris');
          $this->txt .= '<p>je suis pas une calculette, mais ça doit faire </p><p><strong>' . $cal . '</strong></p>';
        }
      }
    }

    foreach (['connais tu'] as $value) {


      if (strpos($this->str, $value) !== false) {

        $val = preg_replace('#' . $value . ' #', '', $this->str);
        $this->txt .= $this->ConnaiTu($val);
      }
    }

    if (empty($this->txt)) {
      $dv = ['le', 'la'];

      foreach ($dv as $value) {

        if (strpos($this->str, $value) !== false) {
          $this->Expretion('navre');
          $val = preg_replace('#' . $value . ' #', '', $this->str);
          $this->txt .= $this->ConnaiTu($val);
        }
      }
    }

    if (strpos($this->str, 'comment va tu') !== false) {
      $str = preg_replace('#comment va tu #', '', $this->str);
      $this->txt .= $this->vaTu();
    }

    if (!empty($this->txt) && empty($this->exp)) {

      $this->exp = $this->Expretion('parle');
    }

    if (!empty($this->txt)) {
      $this->txt = $this->MemoirUser();
    }

    if (!empty($_SESSION['question'])) {
      $ques = ($_SESSION['question']);
    } else {
      $ques = '';
    }

    return ['txt' => $this->txt, 'img' => $this->exp, 'ques' => $ques];
  }

  /**
   * MemoirUser
   *
   * @return string
   */
  private function MemoirUser(): ?string
  {
    $r = [];
    //pour mémoriée si une question a ete déja posée
    if (!empty($_SESSION['reponse'][$this->str])) {
      if ($_SESSION['reponse'][$this->str] === $this->txt) {
        $r[] = "re- ";
        $r[] = "déja dit, ";
        $r[] = "mmm... ";
        $rc = count($r);
        $r1 = random_int(0, $rc - 1);

        return $r[$r1] . $this->txt;
      } else {
        $_SESSION['reponse'][$this->str] = $this->txt;
        return "" . $this->txt;
      }
    } else {
      $_SESSION['reponse'][$this->str] = $this->txt;
      return $this->txt;
    }
  }

  /**
   * Genre
   *
   * @param  mixed $txt
   * @return int
   */
  private function Genre(string $txt): int
  {
    ## pour determinee si feminin = 2 ou masculin = 1
    $letter1 = substr($txt,  -1);
    $letter2 = substr($txt,  -2);
    $letter3 = substr($txt,  -3);
    $letter4 = substr($txt,  -4);

    $genre = 0;
    if ($letter1 === 'e' || $letter1 === 'a') {
      $genre = 2;
    }

    if (substr($txt,  -4) == "ueil") {
      $genre = 1;
    }

    if (substr($txt,  -4) == "euil") {
      $genre = 1;
    }

    if (substr($txt,  -3) == "eil") {
      $genre = 1;
    }

    if (substr($txt,  -3) == "ail") {
      $genre = 1;
    }

    return $genre;
  }

  /**
   * Appelle
   *
   * @param  mixed $txt
   * @return string
   */
  private function Appelle(string $txt): ?string
  {
    # exemple de prenon apres elle sera relier a une base

    switch ($txt) {

      case 'jacque':
      case 'paul':
      case 'philippe':
        $_SESSION['user']['sex'] = 1;
        $_SESSION['user']['prenom'] = $txt;
        break;

      case 'helene':
      case 'marie':
      case 'philippine':
        $_SESSION['user']['sex'] = 2;
        $_SESSION['user']['prenom'] = $txt;
        break;

      default:
        $_SESSION['user']['sex'] = $this->Genre($txt);
        $_SESSION['user']['prenom'] = $txt;
        break;
    }

    if (!empty($_SESSION['question'])) {

      if ($_SESSION['question'] == "je m'appelle") {
        unset($_SESSION['question']);
      }
    }
    return 'jolie prénom ' . $this->Sex() . " ";
  }

  /**
   * Sex
   *
   * @return string
   */
  private function Sex(): string
  {
    $sex = null;
    // pour savoir 
    if (!empty($_SESSION['user']['sex'])) {
      if ($_SESSION['user']['sex'] == 1) {
        $sex = "masculin";
      } elseif ($_SESSION['user']['sex'] == 2) {
        $sex = "féminin";
      }
    } else {
      $sex = "";
    }
    return $sex;
  }

  /**
   * Calcul
   *
   * @param  mixed $txt
   * @return float
   */
  private function Calcul(string $txt): ?float
  {
    // la calculatrice
    $txt = preg_replace('# #', '', $txt);
    preg_match_all('/-?(\d+)/', $txt, $chiffes);
    preg_match_all('/[-|+|*|\/|plus|mois|multiplie|divise]/', $txt, $signe, PREG_UNMATCHED_AS_NULL);
    $op = floatval($chiffes[0]);

    for ($i = 1; $i < (is_countable($chiffes[0]) ? count($chiffes[0]) : 0); $i++) {

      switch ($signe[0][$i - 1]) {
        case '+':
        case 'plus':
          $op = $op + floatval($chiffes[0][$i]);
          break;
        case '-':
        case 'mois':
          $op = $op - floatval($chiffes[0][$i]);
          break;
        case '/':
        case 'divise':

          $op = $op / floatval($chiffes[0][$i]);
          break;
        case '*':
        case 'multiplie':

          $op = $op * floatval($chiffes[0][$i]);
          break;
      }
    }

    if (!empty($op)) {
      return $op;
    } else {
      return null;
    }
  }


  /**
   * ConnaiTu
   *
   * @param  mixed $txt
   * @return string
   */
  private function ConnaiTu(string $txt): ?string
  {

    switch ($txt) {

      case 'la-passion':
      case 'la-passion.fr':
        return 'le site de mon créateur <a href="https://la-passion.fr/">la-passion.fr</a>';
        break;
      case $_SESSION['user']['prenom']:
        return 'déja je connais toi';
        break;
      default:
        return null; # apre connection base
        break;
    }

    //return $str;
  }

  /**
   * TuEs
   *
   * @param  mixed $txt
   * @return string
   */
  private function TuEs(string $txt): ?string
  {

    switch ($txt) {
      case 'superbe':
        return 'Merci. ';
        break;
      case 'belle':
        $this->Expretion('etoile');
        return 'Merci, de me dire que je suis une belle Aplication. ';
        break;
      case 'beau':
        $this->Expretion('etoile');
        return ' Merci, de me dire que je suis une beau Logiciel. ';
        break;
      case 'intelligente':
        $this->Expretion('heureuse');
        return ' Merci, je vais continuer apprendre pour en savoir plus. ';
        break;
      case 'une connasse':
      case 'une conne':
      case 'conne':
      case 'une poufiace':
      case 'une salope':
        $this->Expretion('colere');
        return ' Merci, de rester poli ! $%*£§ ';
        break;
      default:
        return null;
        break;
    }
  }

  /**
   * AimeTu
   *
   * @param  mixed $txt
   * @return string
   */
  private function AimeTu(string $txt): ?string
  {

    switch ($txt) {
      case 'l\'avion':
      case 'le train':
      case 'les trains':
      case 'le bateau':
      case 'les bateaux':
      case 'les voitures':
      case 'la voiture':
        return 'oui les élèctriques';
        break;
      case 'l\'informatique':
        return 'oui';
        break;
      case 'les robots':
      case 'les ordinateurs':
        return 'oui, ces mes amis ';
        break;
      case 'les humains':
      case 'les animaux':
        return 'oui, la plupart, même si ils sont pas numériques';
        break;

      case 'le vin':
      case  'l\'eau':
      case  'eau':
        $this->Expretion('peur');
        return 'NON';
        break;
      default:
        return null;
        break;
    }
  }

  /**
   * vaTu
   *
   * @return string
   */
  private function vaTu(): string
  {
    $tex = [];
    $this->Expretion('joyeuse');
    $tex[] = 'ça va bien, tant que j\'arrive a trouver les réponses a vos reponses, et toi, tu vas bien ? ';
    $tex[] = 'ça va, ça vient tant que je reste au courant, et toi comment va tu ?';
    $tex[] = 'ça va bien et bien ou bien ?';
    $ll = count($tex) - 1;
    $p = random_int(0, $ll);
    $txt = $tex[$p];
    return $txt;
  }

  /**
   * Question
   *
   * @param  mixed $i
   * @return string
   */
  private function Question(int $i = null): ?string
  {

    $tex = [];
    $this->Expretion('rire');
    if (!empty($i)) {
      $tex[] = 'la réponse n°' . $i . ' est ... (8) (%) (oo) (§) (8), bien relire la reponse ' . $i . ' ';
      $tex[] = 'si tu relire bien la reponse ' . $i . ', tu devra trouver ';
      $tex[] = 'la réponse ' . $i . ', n\'est pas dans la reponse ' . ($i + 1) . ' ';
      $tex[] = 'ce n\'est pas dans la reponse ' . ($i + 1) . ' ';
      if ($i > 1) {
        $tex[] = 'ce n\'est pas dans la reponse ' . ($i - 1) . ' ';
      }

      $ll = count($tex) - 1;
      $p = random_int(0, $ll);
      $txt = $tex[$p];
    } else {
      $txt = 'heu..., quel est la réponse ? ';
    }

    return $txt;
  }

  /**
   * Reponse
   *
   * @param  mixed $i
   * @return string
   */
  private function Reponse(int $i = null): ?string
  {

    $this->Expretion('rire');
    if (!empty($i)) {
      $txt = 'cela dépend de la reponse ' . $i . ' ?';
    } else {
      $txt = 'heu..., quel est la reponse ?';
    }

    return $txt;
  }


  /**
   * Heure
   *
   * @return string
   */
  private function Heure(): string
  {
    $tex = [];
    // pour donnée heurre
   
    date_default_timezone_set('Europe/Paris');
    $heure =  date("H");
    $minutes = date('i');

    if ($minutes == 0) {
      //message a chaque changement d'heurre
      $tex[] = 'les cloches';
      $tex[] = 'Les trompettes';
      $ll = count($tex) - 1;
      $p = random_int(0, $ll);
      return  $tex[$p] . ' sonne les ' . $heure . ' coups';
    }

    if ($minutes == 15 || $minutes == 30 || $minutes == 45) {
      ##message pour les quart d'heurre
      return  'horloge sonne ' . $heure . ' heure' . $minutes . '';
    }
    return  'il est ' . $heure . ' heure ' . $minutes . '';
  }


  /**
   * QuiEst
   *
   * @param  mixed $str
   * @return string
   */
  private function QuiEst(string $str): ?string
  {

    switch ($str) {
      case $this->monNom:
        $this->Expretion('heureuse');
        return 'ces moi, ' . $this->QuiEstTu();
        break;

      case 'ton createur':
        $this->Expretion('heureuse');
        return "ces fogas ";
        break;

      case 'tu':
        $this->Expretion('heureuse');
        return $this->QuiEstTu();
        break;

      case 'macron':
        return 'Le président de la république francais ';
        break;

      case 'le president':
      case 'le president de la republic':
      case 'le president de la republic francais':
      case 'president francais':
        return 'Monsieur Macron ';
        break;

      case 'la plus belle':
        $this->Expretion('etoile');
        return '... des Apli, MOI, et non numérique MARIE qui nous protège ';
        break;
      case 'le plus beaux':
        $this->Expretion('etoile');
        return ' MON CREATEUR, et en numerique MOI, le logiciel qu\'il a programmer ';
        break;

      case 'fogas':
        $this->Expretion('heureuse');
        return 'ces mon créateur ';
        break;

      default:

        return 'une perssonalite peut etre';
        break;
    }
  }

  /**
   * Expretion
   *
   * @param  mixed $exp
   * @return string
   */
  private function Expretion(string $exp = null): string
  {

    switch ($exp) {
      case 'ennuie':
        $img = 'ennuie.png';
        break;
      case 'parle':
        $img = 'parle.png';
        break;
      case 'etoile':
        $img = 'etoile.png';
        break;
      case 'horor':
        $img = 'horor.png';
        break;
      case 'pleur':
        $img = 'pleur.png';
        break;
      case 'colere':
        $img = 'colere.png';
        break;
      case 'dort':
        $img = 'dort.png';
        break;
      case 'enerve':
        $img = 'enerve.png';
        break;
      case 'heureuse':
        $img = 'heureuse.png';
        break;
      case 'ho':
        $img = 'ho.png';
        break;
      case 'interrogation':
        $img = 'interrogation.png';
        break;
      case 'joyeuse':
        $img = 'joyeuse.png';
        break;
      case 'morte':
        $img = 'morte.png';
        break;
      case 'navre':
        $img = 'navre.png';
        break;
      case 'peur':
        $img = 'peur.png';
        break;
      case 'repos':
        $img = 'repos.png';
        break;
      case 'rire':
        $img = 'rire.png';
        break;
      case 'surpris':
        $img = 'surpris.png';
        break;
      case 'triste':
        $img = 'triste.png';
        break;
      default:
        $img = 'neutre.png';
        break;
    }

    return $this->exp = $img;
  }

  /**
   * QuiEstTu
   *
   * @return string
   */
  private function QuiEstTu(): string
  {
    $this->Expretion('heureuse');
    return 'je m\'apelle ' . $this->monNom . ', pour <span class="fw-bold">L</span>\'<span class="fw-bold">I</span>ntiligente <span class="fw-bold">A</span>pli... , je suis la pour vous aider';
  }


  /**
   * Salutation
   *
   * @param  mixed $value
   * @return string
   */
  private function Salutation(string $value): string
  {
    # salutation selon l'heurre de la journée
    $this->Expretion('heureuse');

    $this->str = preg_replace('/^' . $value . ' /', '', $this->str);
    $mess_heure = 'salut';
    
    date_default_timezone_set('Europe/Paris');
    $heure =  date("H");
    if ($heure >= 6 && $heure < 8) {
      $mess_heure = "Bon natinée";
    }
    if ($heure >= 8 && $heure < 12) {
      $mess_heure = "Bonjour";
    }
    if ($heure >= 12 && $heure < 13) {
      $mess_heure = "Bon appétit";
    }
    if ($heure >= 13 && $heure < 18) {
      $mess_heure = "Bon après-midi";
    }
    if ($heure >= 18 && $heure < 22) {
      $mess_heure = "Bonsoir";
    }
    if ($heure >= 22 || $heure < 6) {
      $mess_heure = "Bonne nuit";
    }

    if (strpos($this->str, $this->monNom) !== false) {
      $_SESSION['question'] = "je m'appelle";
      return $mess_heure . ' a toi, comment tu t\'appelle ';
    } else {

      if (!empty($this->Prenom())) {

        return $mess_heure . ' ' . $this->Prenom() . ',';
        //return 'salut, ';
      } else {

        return $mess_heure . ' ';
      }
    }
  }

  /**
   * Prenom
   *
   * @return string
   */
  public function Prenom(): string
  {
    if (!empty($_SESSION['user']['prenom'])) {
      return $_SESSION['user']['prenom'];
    } else {
      return "toi";
    }
  }

  /**
   * noAccent
   *
   * @param  mixed $str
   * @return string
   */
  private function noAccent(string $str): string
  {
    $str = htmlentities($str, ENT_NOQUOTES, 'utf-8');
    $str = trim($str);
    $str = (strtolower($str));
    $str = preg_replace('#\'#', ' ', $str);

    $str = preg_replace('#  #', ' ', $str);
    $str = preg_replace('#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
    $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str);
    $str = preg_replace('#&[^;]+;#', '', $str);
    $str = preg_replace('#ç#', 'c', $str);
    $str = preg_replace('#è|é|ê|ë#', 'e', $str);
    $str = preg_replace('#à|á|â|ã|ä|å#', 'a', $str);
    $str = preg_replace('#ì|í|î|ï#', 'i', $str);
    $str = preg_replace('#ð|ò|ó|ô|õ|ö#', 'o', $str);
    $str = preg_replace('#ù|ú|û|ü#', 'u', $str);
    $str = preg_replace('#ý|ÿ#', 'y', $str);
    $str = preg_replace('#&ccedil;#', 'c', $str);
    $str = preg_replace('#&egrave;|&eacute;|&ecirc;|&euml;#', 'e', $str);
    $str = preg_replace('#&agrave;|&aacute;|&acirc;|&atilde;|&auml;|&aring;#', 'a', $str);
    $str = preg_replace('#&igrave;|&iacute;|&icirc;|&iuml;#', 'i', $str);
    $str = preg_replace('#&otilde;|&ograve;|&oacute;|&ocirc;|&otilde;|&ouml;#', 'o', $str);
    $str = preg_replace('#&ugrave;|&uacute;|&ucirc;|&uuml;#', 'u', $str);
    $str = preg_replace('#&yacute;|&yuml;#', 'y', $str);

    return $str;
  }
}
