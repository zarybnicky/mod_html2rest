
.. _k2:

Podkapitola1
------------

.. _k3:

Podkapitola2
------------

.. _k4:

Pooddíl v textu
~~~~~~~~~~~~~~~

.. _k5:

Poslední možná úroveň nadpisů (5)
*********************************

Ostatní úrovně (6,7...) nadpisy budou považovány za obyčejný text.

.. _k6:

Převod písma
------------

Podporován je převod jiného než standardního písma, nejsou jen
podporována písma se znakovou sadou Unicode. Můžete mít například část
odstavce písmem Arial a část písmem Courier.

Písmo se převádí po slovech tzn. pokud označíme celé slovo a část
druhého, převedou se obě slova do stejného písma, toho kterým slovo
začíná.

.. _k7:

Podpora vkládání speciálních znaků
----------------------------------

Stačí, když uživatelé \ **nebudou**\  vkládat symboly z nabídky vložit
-> symbol. Místo tohostačí psát \ **HTML entity**\  přímo do dokumentu.

Více informací o entitách naleznete na
`http://www.w3.org/TR/REC-html40/sgml/entities.html
<http://www.w3.org/TR/REC-html40/sgml/entities.html>`_

`\ **Zde**\ <http://www.net-university.cz/navody/spec_znaky.pdf>`_ **uvidíte
seznam všech entit (seznam čítá 5000 položek) kdy na levé straně je
vidět znak, který uvidíte na internetu a v pravo je jeho** \ **HTML entita**\
 \ **.** \ **Je nutné aby entita byla napsána přesně tak jak ji uvidíte v
seznamu tj. musí začínat znakem** \ **&**\   \ **a končit znakem** \ **;**\

Příklad napsání znaku α pomocí entity.Stačí napsat &#945; a na internetu
se zobrazí znak α.

.. _k8:

Vkládání tabulek
----------------

Tabulky lze do textu prostřednictvím šablony vkládat dvěma způsoby:


1. \ **Pomocí převedení na obrázek**\  (dříve jediná možná alternativa).
   Tento styl je nadále možný a lze jej použít u velmi složitých tabulek,
   které obsahují formátování, které konvertor nepřevede (viz nepodporované
   formátování pro tabulky)
2. \ **Vytvořením tabulky pomocí MS Word**\ . Při vytváření je třeba se
   řídit některými pravidly.


.. _k9:

Pravidla pro vytvoření tabulek pomocí MS Word pro šablonu Uniforu
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~


- Do textu je možné vkládat tabulky jednoduché tak i tabulky vnořené(viz obr
  1).


+-----------------------------------+------+------+------+------+
| +------+------+-----------------+ | text | Text | Text | text |
| | Text | text | text            | |      |      |      |      |
| +------+------+-----------------+ |      |      |      |      |
| | Text | text | +------+------+ | |      |      |      |      |
| |      |      | | text | text | | |      |      |      |      |
| |      |      | +------+------+ | |      |      |      |      |
| |      |      | | text | text | | |      |      |      |      |
| |      |      | +------+------+ | |      |      |      |      |
| +------+------+-----------------+ |      |      |      |      |
+-----------------------------------+------+------+------+------+
| Text                              | text | Text | text | text |
+-----------------------------------+------+------+------+------+

Obr 1.

.. _k10:

V tabulce můžeme použít následující formátování:
************************************************


- barvu pozadí jak celé tabulky tak jednotlivých buněk,
- \ **tučné písmo,**
- \ *Italiku*\ ,
- \ :under:`podtržené`\  \ :under:`písmo`\
- barvu písma.
- Změnu písma
- je podporována šířka okraje tabulky


.. _k11:

Na co si dejte pozor
~~~~~~~~~~~~~~~~~~~~


- tabulka \ **nesmí**\  být v nadpisu.
- nepoužívejte slučované buňky viz obr 2 (nepoužitelné) a obr 3 (vhodná
  realizace)


Obr 2. sloučená buňka se nepřevede

Obr 3. tabulka je složena ze dvou existujících tabulek

.. _k12:

Nepodporované formátování pro tabulky
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~


- tloušťka čar buněk podporována není


.. _k13:

Tip
~~~

Pomocí tabulek bez ohraničení lze provádět formátování stránek či
nahradit chybějící tabelátory. Pokud chcete například odskočit text lze
to provést pomocí tabulky se dvěma sloupci kdy první sloupec je prázdný a
druhý obsahuje text. Ohraničení tabulky musí být vypnuto jinak bude vidět,
že jde o tabulku. Viz obr 4.

+---------+
| 1 řádek |
+---------+
| 2 řádek |
+---------+

Obr 4.


.. _k15:

Zarovnání textu
---------------

Současná verze tutora neumí různé typy zarovnání.

Bylo by dobré, kdybys zvládl zarovnávat i doprava.

Na střed by se také hodilo.

A o standardním levém zarovnání ani nemluvím.

Zarovnání do bloku je na zvážení, ale možná bych to uživatelů umožnil.
Bohužel teď musím napsat delší text, abych měl vizuální potvrzení, že
jsem klikl na správnou ikonku. To by mohlo stačit.

.. _k16:

Vkládání materiálů
------------------

Obrázky dozajista vkládáš …

|..//31FE6C29-C095-4AB4-BF53-B34D5328AB17.jpg|

… ale tutor umí i vzít soubor, nahrát ho na server a `nalinkovat
<http://localhost/RESOURCE/4A58C23F-8C53-4EBC-9021-5594E1F1A297.pdf>`_.

.. _k17:

Vnořené seznamy
---------------

A kdyby se ti náhodou podařilo korektně převádět vnořené seznamy, také
by to pomohlo.


1. Jedna
2. Dva
3. 1. Tři
   2. Čtyři
4. Pět



1. Cos to
2. Honzo
3. Cos to
4. Sněd



1. Brambory
2. Pečený
3. Byly
4. Málo
5. Maštěný
6. Cos
7. To
8. Janku Sněd?



1. Xsx
2. Xsx


.. _k18:

Úkoly
-----

Možná chybí i zadávání úkolů. Abys to neměl tak jednoduché, existuje
mnoho variant.

.. _k19:

Běžné úkoly
~~~~~~~~~~~

.. dnote::
   :icon: TEMPLATEimages/text_icons/6b.gif
   :link: http://localhost/index.php?pageid=5207&onlycontent=1&task_id={{task-short-0}}&id_dbound={{dbound-3}}

.. dnote::
   :icon: TEMPLATEimages/text_icons/6d.gif
   :link: http://localhost/index.php?pageid=5207&onlycontent=1&task_id={{task-long-0}}&id_dbound={{dbound-3}}

.. _k20:

Parametrizované úkoly
~~~~~~~~~~~~~~~~~~~~~

Již v šabloně můžete nastavit parametry úkolu (dlouhého i krátkého).
Činí se tak závorkou na konci úkolu ve formátu (Název úkolu;Počet
bodů;Úroveň;Volitelný x Povinný). Závorka je součástí odstavce úkol
(je na stejném řádku jako poslední věta zadání). V závorce musí být
vyplněn buď pouze Název úkolu, nebo všechny 4 parametry. Není možné
použít třeba jen body a volitelnost. Uzavírací znak ")" nesmí být
formátován, tzn. že můžete použít pouze styl úkolu. Při použití
tučného, podtrženého,... písma nebude převod parametrů fungovat.

.. _k21:

Krátké úkoly
************

.. dnote::
   :icon: TEMPLATEimages/text_icons/6b.gif
   :link: http://localhost/index.php?pageid=5207&onlycontent=1&task_id={{task-short-1}}&id_dbound={{dbound-3}}

   Příklad použití: Pokusný úkol.

.. _k22:

Dlouhé úkoly
************

Pomocí parametrů je možné ovlivnit zobrazení dlouhých úkolů přímo v
textu. Nové parametry Ignorovat popis a Použít název se uvádějí do
závorky za parametry ostatní. Příklad využití vidíte na následujících
obrázcích.

Standardní zadání se všemi parametry:

.. dnote::
   :icon: TEMPLATEimages/text_icons/6d.gif
   :link: http://localhost/index.php?pageid=5207&onlycontent=1&task_id={{task-long-1}}&id_dbound={{dbound-3}}

   Pokuste se vytvořit analogii mezi uvedenými typy školního klimatu a
   mateřskou školou. Určete, ke kterému typu školního klimatu patří Vaše
   mateřská škola, na které působíte? Charakterizujte typ jejího školního
   klimatu.

Parametr Ignorovat popis zanechá v textu pouze ikonu úkolu:

.. dnote::
   :icon: TEMPLATEimages/text_icons/6d.gif
   :link: http://localhost/index.php?pageid=5207&onlycontent=1&task_id={{task-long-2}}&id_dbound={{dbound-3}}

Parametr Použít název zanechá v textu název úkolu s ikonou:

.. dnote::
   :icon: TEMPLATEimages/text_icons/6d.gif
   :link: http://localhost/index.php?pageid=5207&onlycontent=1&task_id={{task-long-3}}&id_dbound={{dbound-3}}

   Testovací název

Tyto parametry je možné použít pouze v kombinaci se všemi ostatními. Není
tedy možné použít pouze \ *Název úkolu*\  a \ *Ignorovat popis*\  nebo \
*Použít název*\ .

Vzor i s obrázky na jdeš na
`http://lmsunifor.com/autor-cd/2007/dokumenty/styly.htm
<http://lmsunifor.com/autor-cd/2007/dokumenty/styly.htm>`_.

.. |..//31FE6C29-C095-4AB4-BF53-B34D5328AB17.jpg| image:: ..//31FE6C29-C095-4AB4-BF53-B34D5328AB17.jpg
