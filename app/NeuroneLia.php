<?php

declare(strict_types=1);


class NeuroneLia
{
  public ?string $repo = null;
  public string|null|array $ques = null;
  public string $monNom = 'lia';
  public $exp;

  public function __construct()
  {
  }

  /**
   * QuestionPosee
   *
   * @param  mixed $str
   * @return void
   */
  public function QuestionPosee(string $str): void
  {
    $this->ques = $str;
  }

  /**
   * Merci
   *
   * @param  mixed $str
   * @return string
   */
  public function Merci(string $str): ?string
  {
    $re = [];
    $lia = new Lia();
    $this->exp = $lia->Expretion('joyeuse');
    $re[] = 'de rien';
    $re[] = 'au plaisir';

    $rc = count($re);
    $r1 = random_int(0, $rc - 1);

    return $re[$r1];
  }

  /**
   * Coucou
   * delire sur le coucou
   * @param  mixed $str
   * @return string
   */
  public function Coucou(string $str): ?string
  {
    $re = [];
    $lia = new Lia();
    $this->exp = $lia->Expretion('rire');

    date_default_timezone_set('Europe/Paris');

    if (date('i') === 0) {
      $heure =  date("G");
      $cou = null;
      for ($h = 1; $h <= $heure; $h++) {
        $cou .= 'coucou ';
      }
      $re[] = '' . $cou . ', il est ' . $heure . ' heures';
      $re[] = 'fait le ' . $heure . ' fois';
      $re[] = 'fait le suisse';
      $re[] = 'fait l\'horloge suisse';
    } else {
      $re[] = 'fait l\'oiseau';
      $re[] = 'fait le hibou';
      $re[] = 'cui-cui fait l\'oiseau';
    }

    $rc = count($re);
    $r1 = random_int(0, $rc - 1);

    return $re[$r1];
  }


  /**
   * Calcul
   * calculatrice
   * @param  mixed $txt
   * @return float
   */
  public function Calcul(string $txt): float|string|null
  {

    $txt = str_replace(['mois', 'plus', 'multiplier par', 'multiplie', 'diviser par', 'divise'], ['-', '+', '*', '*', '/', '/'], $txt);
    $txt = preg_replace('/\s+/', '', $txt);

    $chiffres = [];

    preg_match_all('/\d+/', $txt, $chiffres);

    $signes = preg_split('/\d+/', $txt, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
    $op = (float) $chiffres[0][0];
    $itemsCount = is_countable($chiffres[0]) ? count($chiffres[0]) : 0;

    for ($i = 1; $i < $itemsCount; $i++) {
      $value = (float) $chiffres[0][$i];
      $signe = $signes[$i - 1];

      switch ($signe) {
        case '+':
          $op += $value;
          break;
        case '-':
          $op -= $value;
          break;
        case '*':
          $op *= $value;
          break;
        case '/':
          if ($value === 0) {
            return "Erreur : division par zéro";
          }
          $op /= $value;
          break;
      }
    }

    return (string)(round($op, 2));
  }

  /**
   * ConnaiTu
   *reponse 
   * @param  mixed $txt
   * @return string
   */
  public function ConnaiTu(string $txt): ?string
  {

    $txt = $this->delArticleDefini($txt);
    //var_dump($txt);


    if (empty($str)) {
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
    }

    return $str;
  }

  /**
   * TuEs
   *reponse a tu est 
   * @param  mixed $txt
   * @return string
   */
  public function TuEs(string $txt): ?string
  {
    $lia = new Lia();
    // $txt = trim(preg_replace('#' . $txt . '#', '', $this->ques));

    switch ($txt) {
      case 'la':
        return 'Oui';
        break;
      case 'superbe':
        return 'Merci. ';
        break;
      case 'belle':
        $this->exp = $lia->Expretion('etoile');
        return 'Merci, de me dire que je suis une belle Aplication. ';
        break;
      case 'beau':
        $this->exp = $lia->Expretion('etoile');
        return ' Merci, de me dire que je suis une beau Logiciel. ';
        break;
      case 'intelligente':
        $this->exp = $lia->Expretion('heureuse');
        return ' Merci, je vais continuer apprendre pour en savoir plus. ';
        break;

      case 'conne':
      case 'conard':
        $this->exp = $lia->Expretion('colere');

        return $this->Insulte($txt);
        break;
      default:
        return null;
        break;
    }
  }

  /**
   * Insulte
   *
   * @param  mixed $txt
   * @return string
   */
  public function Insulte(string $txt): ?string
  {
    $lia = new Lia();
    $this->exp = $lia->Expretion('colere');
    $txt = $this->delArticleDefini($txt);

    switch ($txt) {

      case 'connasse':
      case 'connard':
        $insulte = $txt . " non numérique, toi meme";
        if (isset($_SESSION['user']['sex'])) {

          if ($_SESSION['user']['sex'] === 'f') {
            $insulte = "connasse non numérique";
          }
          if ($_SESSION['user']['sex'] === 'm') {
            $insulte = "connard non numérique";
          }
        }
        break;

      case 'conne':
        $insulte = $txt . " non numérique, toi meme";
        if (isset($_SESSION['user']['sex'])) {

          if ($_SESSION['user']['sex'] === 'f') {
            $insulte = "conne non numérique";
          }
          if ($_SESSION['user']['sex'] === 'm') {
            $insulte = "connard non numérique";
          }
        }
      case 'poufiace':
      case 'pouffiace':
      case 'pouffe':
        $insulte = $txt . " non numérique, toi meme";
        if (isset($_SESSION['user']['sex'])) {

          if ($_SESSION['user']['sex'] === 'f') {
            $insulte = "poufiace non numérique";
          }
          if ($_SESSION['user']['sex'] === 'm') {
            $insulte = "pouffiace non numérique";
          }
        }
        break;
      case 'salope':
        $insulte = $txt . " non numérique, toi meme";
        if (isset($_SESSION['user']['sex'])) {

          if ($_SESSION['user']['sex'] === 'f') {
            $insulte = "salope non numérique";
          }
          if ($_SESSION['user']['sex'] === 'm') {
            $insulte = "salaud non numérique";
          }
        }
        break;
      case 'vieille vache':

        $insulte = $txt . " non numérique, toi meme";
        if (isset($_SESSION['user']['sex'])) {

          if ($_SESSION['user']['sex'] === 'f') {
            $insulte = "vielle vache non numérique";
          }
          if ($_SESSION['user']['sex'] === 'm') {
            $insulte = "vieux tareaux non numérique";
          }
        }
        break;
      case 'vieille bique':
      case 'vieille bouc':
        $insulte = $txt . " non numérique, toi meme";
        if (isset($_SESSION['user']['sex'])) {

          if ($_SESSION['user']['sex'] === 'f') {
            $insulte = "vielle bique non numérique";
          }
          if ($_SESSION['user']['sex'] === 'm') {
            $insulte = "vieux bouc non numérique";
          }
        }
        break;
      default:
        //$insulte = "reste t'on polie %§£!$, toi meme";
        $insulte = null;
        break;
    }

    return $insulte;
  }

  /**
   * AimeTu
   *réponse aime tu
   * @param  mixed $txt
   * @return string
   */
  public function AimeTu(string $txt): ?string
  {
    $lia = new Lia();
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
      case 'la neige':
        $this->exp = $lia->Expretion('peur');
        return 'NON';
        break;
      default:
        return null;
        break;
    }
  }

  /**
   * vaTu
   *reponse a comment va tu
   */
  public function vaTu(): string
  {
    $lia = new Lia();
    $tex = [];
    $this->exp = $lia->Expretion('joyeuse');
    $tex[] = 'chat va bien, tant que j\'arrive a trouver les réponses a vos questions, et toi, tu vas bien ? ';
    $tex[] = 'ça va, ça vient tant que je reste au courant, et toi comment va tu ?';
    $tex[] = 'ça va bien et bien ou bien ?';
    $ll = count($tex) - 1;
    $p = random_int(0, $ll);
    return $tex[$p];
  }

  /**
   * Question
   *reponse a la question numerotee
   * @param  mixed $i
   * @return string
   */
  public function Question(string $i = null): ?string
  {
    $i = (int) $i;
    $lia = new Lia();
    $tex = [];
    $this->exp = $lia->Expretion('rire');
    if (!empty($i)) {
      $tex[] = 'la réponse n°' . $i . ' est ... (8) (%) (oo) (§) (8), bien relire la question ' . $i . ' ';
      $tex[] = 'si tu relire bien la question ' . $i . ', tu devra trouver ';
      $tex[] = 'la réponse ' . $i . ', n\'est pas dans la question ' . ($i + 1) . ' ';
      $tex[] = 'ce n\'est pas dans la question ' . ($i + 1) . ' ';
      if ($i === 42) {
        $this->exp = $lia->Expretion('rire');
        $tex[] = 'd\'apres le guide du voyageur galactique ces la "la grande question sur la vie, l’univers et le reste" ';
      }
      if ($i > 1) {
        $tex[] = 'ce n\'est pas dans la question ' . ($i - 1) . ' ';
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
   * Questions
   *reponse A LA QUESTION 
   * @param  mixed $txt
   * @return string
   */
  public function Questions(string $txt): ?string
  {
    $lia = new Lia();
    $tex = [];
    $this->exp = $lia->Expretion('rire');
    if ($txt === 'sur la vie, l\'univers et le reste') {

      $tex[] = 'd\'apres le guide du voyageur galactique <h1>42</h1> ';
    }
    $tex[] = '<h1>42</h1>';

    $ll = count($tex) - 1;
    $p = random_int(0, $ll);


    return $tex[$p];
  }

  /**
   * Reponse
   *reponse a la reponse numerotée
   * @param  mixed $i
   * @return string
   */
  public function Reponse(string $i = null): ?string
  {
    $i = (int) $i;
    $lia = new Lia();
    $tex = [];
    $this->exp = $lia->Expretion('rire');
    if (!empty($i)) {
      if ($i === 42) {
        $this->exp = $lia->Expretion('rire');
        $tex[] = 'd\'apres le guide du voyageur galactique ces la "la grande question sur la vie, l’univers et le reste" ';
      }
      $tex[] = 'cela dépend de la question ' . $i . ' ?';
      $ll = count($tex) - 1;
      $p = random_int(0, $ll);
      $txt = $tex[$p];
    } else {
      $txt = 'heu..., quel est la question ?';
    }

    return $txt;
  }
  /**
   * Reponses
   *reponse A LA QUESTION
   * @param  mixed $txt
   * @return string
   */
  public function Reponses(string $txt): ?string
  {
    $lia = new Lia();
    $tex = [];
    $this->exp = $lia->Expretion('rire');

    if ($txt === 'sur la vie, l\'univers et le reste') {
      $this->exp = $lia->Expretion('rire');
      $tex[] = 'd\'apres le guide du voyageur galactique <h1>42</h1> ';
    }
    $tex[] = '<h1>42</h1>';
    $ll = count($tex) - 1;
    $p = random_int(0, $ll);


    return $tex[$p];
  }


  /**
   * Heure
   *donne l'heurre
   */
  public function Heure(): ?string
  {
    $tex = [];
    date_default_timezone_set('Europe/Paris');
    $heure =  date("G");
    $minutes = date('i');

    if ($minutes === 0) {
      $tex[] = 'fourviere';
      $tex[] = 'saint jean';
      $tex[] = 'La trompette de l\'horloge de Guignol';
      $ll = count($tex) - 1;
      $p = random_int(0, $ll);
      return  $tex[$p] . ' sonne les ' . $heure . ' coups';
    }

    if ($minutes === 15 || $minutes === 30 || $minutes === 45) {

      return  'horloge de Guignol sonne ' . $heure . ' heure' . $minutes . '';
    }
    $tex[] = 'je remplace pas l\'horloge parlante, il est ';
    $tex[] = 'je suis pas l`horloge parlante, il est ';
    $tex[] = 'au quatrieme top il sera, ';
    $tex[] = 'il est ';
    $ll = count($tex) - 1;
    $p = random_int(0, $ll);
    return  $tex[$p] . $heure . ' heure ' . $minutes . '';
  }


  /**
   * QuiEst
   *donne qui est une perssonne
   * @param  mixed $str
   * @return string
   */
  public function QuiEst(string $str): ?string
  {

    $lia = new Lia();
    switch ($str) {
      case $this->monNom:
        $this->exp = $lia->Expretion('heureuse');
        return 'ces moi, ' . $this->QuiEstTu();
        break;

      case 'ton createur':
        $this->exp = $lia->Expretion('heureuse');
        return "ces Philippe Fogas ";
        break;

      case 'tu':
        $this->exp = $lia->Expretion('heureuse');
        return $this->QuiEstTu();
        break;

      case 'le president':
        return 'le président de la république française et Monsieur ???, mais le mien et mon créateur';
        break;

      case 'la plus belle':
        $this->exp = $lia->Expretion('etoile');
        return '... des Apli, MOI, et en non numérique MARIE qui nous protège ';
        break;
      case 'le plus beau':
        $this->exp = $lia->Expretion('etoile');
        return ' MON CREATEUR, et en numerique MOI, le logiciel qu\'il a programmer ';
        break;

      case 'philippe brial':
        $this->exp = $lia->Expretion('heureuse');
        return 'ces mon créateur ';
        break;

      default:

        if (!empty($_SESSION['user']['prenom'])) {
          $this->exp = $lia->Expretion('heureuse');
          $ce = 'Déja toi, ';
        }
        return $ce;
        break;
    }
  }


  /**
   * QuiEstTu
   *
   * @return string
   */
  public function QuiEstTu(): ?string
  {
    $lia = new Lia();
    $this->exp = $lia->Expretion('heureuse');
    return 'je m\'apelle Lia, pour <strong>L</strong>\'(<strong>I</strong>ntiligente/Incroyable/Inpitoyabe/Insolante/In etc..) <strong>A</strong>pli, une apllication Lyonnaise de la-passion.fr, je suis la pour vous aider';
  }


  /**
   * Salutation
   *pour saluer selon l'heurre de la journee
   * @param  mixed $value
   */
  public function Salutation(string $value): ?string
  {
    $lia = new Lia();
    $this->exp = $lia->Expretion('heureuse');
    $this->ques = preg_replace('/^' . $value . ' /', '', $this->ques);
    $messHeure = 'salut';
    date_default_timezone_set('Europe/Paris');
    $heure =  date("G");
    if ($heure >= 6 && $heure < 8) {
      $messHeure = "bon natinée";
    }
    if ($heure >= 8 && $heure < 12) {
      $messHeure = "bonjour";
    }
    if ($heure >= 12 && $heure < 13) {
      $messHeure = "bon appétit";
    }
    if ($heure >= 13 && $heure < 18) {
      $messHeure = "bon après-midi";
    }
    if ($heure >= 18 && $heure < 22) {
      $messHeure = "bonsoir";
    }
    if ($heure >= 22 || $heure < 6) {
      $messHeure = "bonne nuit";
    }
    if (($value != 'salut' || $value != 'chalut') && $value != $messHeure) {
      $messHeure = "vu l'heurre, " . $messHeure;
    }
    if (str_contains($this->ques, $this->monNom)) {
      $_SESSION['question'] = "je m'appelle ";
      return $messHeure . ' est toi, comment tu t\'appelle ';
    } elseif (!empty($this->Prenom())) {
      return $messHeure . ' ' . $this->Prenom() . ',';
      //return 'salut, ';
    } else {

      return $messHeure . ' ';
    }
  }

  /**
   * Prenom
   *
   * @return string
   */
  public function Prenom(): ?string
  {
    if (!empty($_SESSION['user']['prenom'])) {
      return $_SESSION['user']['prenom'];
    } else {
      return null;
    }
  }

  /**
   * Appelle
   *
   * @param  mixed $txt
   * @return string
   */
  public function Appelle(string $txt): ?string
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


    if (!empty($_SESSION['question']) && $_SESSION['question'] == "je m'appelle") {

      unset($_SESSION['question']);
    }
    $apel = 'jolie prénom ' . $this->Sex() . ' ';

    if (!empty($_SESSION['user']['prenom'])) {
      $apel = $_SESSION['user']['prenom'] == $txt ? 'je sais ' : 'ces pas ' . $_SESSION['user']['prenom'] . ' ';
    }

    $_SESSION['user']['prenom'] = $txt;
    return $apel;
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

    if (substr($txt,  -4) === "ueil") {
      $genre = 1;
    }

    if (substr($txt,  -4) === "euil") {
      $genre = 1;
    }

    if (substr($txt,  -3) === "eil") {
      $genre = 1;
    }

    if (substr($txt,  -3) === "ail") {
      $genre = 1;
    }

    return $genre;
  }
  /**
   * Sex
   */
  public function Sex(): ?string
  {
    $sex = null;
    // pour savoir 
    if (!empty($_SESSION['user']['sex'])) {
      if ($_SESSION['user']['sex'] === 'm') {
        $sex = "masculin";
      } elseif ($_SESSION['user']['sex'] === 'f') {
        $sex = "féminin";
      } elseif ($_SESSION['user']['sex'] === 'a') {
        $sex = "féminin/masculin";
      }
    } else {
      $sex = "";
    }
    return $sex;
  }

  /**
   * dalarticleDefini
   *  retire article defini (de la, du, la, le , l' ,de l',un, une) 
   * @param  mixed $valu
   * @return string
   */
  public function delArticleDefini(string $str): ?string
  {
    if (empty($str)) {
      return null;
    }

    $articles = [
      'de' => '',
      'du' => '',
      'de la' => '',
      'la' => '',
      'les' => '',
      'le' => '',
      'l\'' => '',
      'd\'' => '',
      'une' => '',
      'un' => ''
    ];

    // Supprimer les articles définis du début de la chaîne
    foreach ($articles as $article => $replacement) {
      $pattern = '/^' . preg_quote($article, '/') . '/i';
      $str = preg_replace($pattern, $replacement, $str);
    }

    return trim($str);
  }

  /**
   * delArticleDefin
   * retire article defini qui est devant le mot
   * @param  mixed $str
   * @return string
   */
  public function delArticleDefin(string $str): ?string
  {

    $article = ['de', 'du', 'de la', 'la', 'le', 'l ', 'l\'', 'd ', 'd\'', 'les'];
    foreach ($article as $d) {
      if (str_contains($str, $d)) {
        $str = trim(preg_replace('#' . $d . ' #', '', $str));
      }
    }

    return $str;
  }
}
