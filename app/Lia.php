<?php

declare(strict_types=1);

require_once 'app/Database.php';

//use Database;

class Lia extends Database
{

  private $txt;
  private $str;
  private $monNom = 'lia';
  protected $exp;

  public function __construct()
  {
    parent::__construct();
  }

  public function jsDec(string $str)
  {
    $res = $this->Dec($str);
    return json_encode($res);
  }

  public function Dec(string $str): array
  {

    $str = preg_replace('/[;:?!]/i', '', $str);
    $str = $this->noAccent($str);


    $this->str = ($str);

    $salut = ['bonjour', 'salut', 'salutation', 'bonsoir', 'chalut'];

    foreach ($salut as $value) {

      if (strpos($this->str, $value) !== false) {

        $this->txt = trim($this->Salutation($salut, $value));
      }
    }


    if (strpos($this->str, 'qui est') !== false) {
      $str = trim(preg_replace('#qui est #', '', $this->str));
      $this->txt .= $this->QuiEst($str);
    }

    if (strpos($this->str, 'merci') !== false) {
      $this->txt .= ' de rien ';
    }

    if (strpos($this->str, 'aime tu') !== false) {
      $str = trim(preg_replace('#aime tu #', '', $this->str));
      $this->txt .= $this->AimeTu($str);
    }


    $r = ['la reponse', 'la reponse n°', 'la reponse numero', 'la reponse a la question'];

    foreach ($r as $value) {

      if (strpos($this->str, $value) !== false) {
        preg_match('/[0-9]{1,2}/', $this->str, $matches);
        $this->txt = $this->Reponse((int) $matches[0] ?? 0);
      }
    }

    $q = ['la question', 'la question n°', 'la question numero'];

    foreach ($q as $value) {

      if (strpos($this->str, $value) !== false) {
        preg_match('/[0-9]{1,2}/', $this->str, $matches);
        $this->txt = $this->Question((int) $matches[0] ?? 0);
      }
    }

    $h = ['quel heure est il', 'il est quel heure', 'y est quel heure', 'c\'est quelle heure'];

    foreach ($h as $value) {

      if (strpos($this->str, $value) !== false) {

        $this->txt .= $this->Heure();
      }
    }


    $dj = ['tu es', 'tu est'];
    foreach ($dj as $value) {
      if (strpos($this->str, $value) !== false) {
        $val = trim(preg_replace('#' . $value . ' #', '', $this->str));

        $this->txt .= $this->TuEs($val);
      }
    }


    foreach (['calcul'] as $value) {

      if (strpos($this->str, $value) !== false) {
        $val = trim(preg_replace('#' . $value . ' #', '', $this->str));
        $cal = $this->Calcul($val);
        if (!empty($cal)) {
          $this->Expretion('surpris');
          $this->txt .= '<p>je suis pas une calculette, mais cha doit faire </p><p><strong>' . $cal . '</strong></p>';
        }
      }
    }



    if (empty($this->txt)) {
      $dv = ['le', 'la'];

      foreach ($dv as $value) {

        if (strpos($this->str, $value) !== false) {
          $this->Expretion('navre');
          $val = trim(preg_replace('#' . $value . ' #', '', $this->str));
          $this->txt .= $this->ConnaiTu($val);
        }
      }
    }



    if (strpos($this->str, 'comment va tu') !== false) {
      $str = trim(preg_replace('#comment va tu #', '', $this->str));
      $this->txt .= $this->vaTu();
    }



    if (!empty($this->txt) && empty($this->exp)) {
      $this->exp = $this->Expretion('parle');
    }
    return ['txt' => $this->txt, 'img' => $this->exp];
  }



  private function Calcul(string $txt): ?float
  {
    preg_match_all('/-?(\d*\.\d+)/', $txt, $chiffes);
    preg_match_all('/[-|+|*|\/|plus|mois|multiplie|divise|diviser par|multiplier par]/', $txt, $signe);
    $op = floatval($chiffes[0][0]);

    for ($i = 1; $i < count($chiffes[0]); $i++) {

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
        case 'diviser par':
          $op = $op / floatval($chiffes[0][$i]);
          break;
        case '*':
        case 'multiplie':
        case 'multiplier par':
          $op = $op * floatval($chiffes[0][$i]);
          break;
        default:
          $op = $op;
          break;
      }
    }

    if (!empty($op)) {
      return $op;
    } else {
      return null;
    }
  }


  private function ConnaiTu(string $txt): ?string
  {

    switch ($txt) {

      case 'la-passion':
      case 'la-passion.fr':
      case 'passion':
        return 'le site de mon créateur <a href="https://la-passion.fr/">la-passion.fr</a>';
        break;

      default:
        return null;
        break;
    }

    //return $str;
  }

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

  private function vaTu(): string
  {
    $this->Expretion('joyeuse');
    $tex[] = 'chat va bien, tant que j\'arrive a trouver les réponses a vos questions, et toi, tu vas bien ? ';
    $tex[] = 'ça va, ça vient tant que je reste au courant, et toi comment va tu ?';
    $tex[] = 'ça va bien et bien ou bien ?';
    $ll = count($tex) - 1;
    $p = rand(0, $ll);
    $txt = $tex[$p];
    return $txt;
  }

  private function Question(int $i = null): ?string
  {

    $this->Expretion('rire');
    if (!empty($i)) {
      $tex[] = 'la réponse n°' . $i . ' est ... (8) (%) (oo) (§) (8), bien relire la question ' . $i . ' ';
      $tex[] = 'si tu relire bien la question ' . $i . ', tu devra trouver ';
      $tex[] = 'la réponse ' . $i . ', n\'est pas dans la question ' . ($i + 1) . ' ';
      $tex[] = 'ce n\'est pas dans la question ' . ($i + 1) . ' ';
      if ($i > 1) {
        $tex[] = 'ce n\'est pas dans la question ' . ($i - 1) . ' ';
      }

      $ll = count($tex) - 1;
      $p = rand(0, $ll);
      $txt = $tex[$p];
    } else {
      $txt = 'heu..., quel est la réponse ? ';
    }

    return $txt;
  }

  private function Reponse(int $i = null): ?string
  {

    $this->Expretion('rire');
    if (!empty($i)) {
      $txt = 'cela dépend de la question ' . $i . ' ?';
    } else {
      $txt = 'heu..., quel est la question ?';
    }

    return $txt;
  }


  private function Heure(): string
  {
    date_default_timezone_set('UTC');
    $heure = round(date("H") + (date("z") / 120), 0);
    $minutes = date('i');

    if ($minutes == 0) {
      $tex[] = 'les cloches';

      $tex[] = 'Les trompettes';
      $ll = count($tex) - 1;
      $p = rand(0, $ll);
      return  $tex[$p] . ' sonne les ' . $heure . ' coups';
    }

    if ($minutes == 15 || $minutes == 30 || $minutes == 45) {

      return  'horloge sonne ' . $heure . ' heure' . $minutes . '';
    }
    return  'il est ' . $heure . ' heure ' . $minutes . '';
  }


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

        return 'une perssonalite';
        break;
    }
  }

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

  private function QuiEstTu(): string
  {
    $this->Expretion('heureuse');
    return 'je m\'apelle Lia, pour <span class="fw-bold">L</span>\'<span class="fw-bold">I</span>ntiligente <span class="fw-bold">A</span>pli... , je suis la pour vous aider';
  }


  private function Salutation(array $salut, string $value): string
  {
    $nom = null;
    $this->Expretion('heureuse');
    if (in_array($value, $salut) === true) {
      $nom = trim(preg_replace('/^' . $value . ' /', '', $this->str));
    }

    $this->str = trim(preg_replace('/^' . $value . ' /', '', $this->str));

    if (strpos($this->str, $this->monNom) !== false) {
      return 'salut a toi ';
    } else {

      if (!empty($nom)) {
        //var_dump($nom);

        $out = explode(' ', $nom);
        // var_dump($out);
        $this->str = trim(preg_replace('#' . $out[0] . ' #', '', $this->str));

        //return 'salut, moi je m\'appele pas ' . $nom . ',';
        return 'salut, ';
      } else {
        return 'chalut, ';
      }
    }
  }

  private function noAccent(string $str): string
  {
    $str = htmlentities($str, ENT_NOQUOTES, 'utf-8');
    $str = trim($str);
    $str = (strtolower($str));
    //$str = preg_replace('#-#', ' ', $str);

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
