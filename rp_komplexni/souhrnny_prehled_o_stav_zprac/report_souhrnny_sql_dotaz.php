<?php
function sqlSouhrRepCelkem($jmeno_tab) {
  return "select sum(vs),
         sum(web),
         sum(pdf),
         sum(ostatni),
         sum(celkem),
         to_char(CASE WHEN sum(vs)=0 THEN 0 ELSE round(sum(celkem) / sum(vs)*100, 2) END, '990.99'),
         'doplnit' as doplnit,
         sum(eqa3),
         sum(eqa4),
         sum(eqa5),
         sum(eqa6),
         to_char(CASE WHEN sum(eqa3 + eqa4 + eqa5 + eqa6)=0 THEN 0 ELSE round(sum(eqa4 + eqa5 + eqa6)/sum(eqa3 + eqa4 + eqa5 + eqa6)*100, 2) END, '990.99') as \"%>3\",
         sum(akt00),
         sum(akt11),
         sum(akt12),
         sum(akt13),
         sum(akt21),
         sum(akt22),
         sum(akt24),
         sum(akt25),
         sum(akt26),
         sum(akt27),
         sum(akt31),
         sum(akt32),
         sum(akt33),
         sum(akt36),
         sum(akt37),
         sum(akt38),
         to_char(CASE WHEN sum(akt00 + akt11 + akt12 + akt13)=0 THEN 0 ELSE round(sum(akt21 + akt22 + akt24 + akt25 + akt26 + akt27 + akt31 + akt32 + akt33 + akt36 + akt37 + akt38)/sum(akt00 + akt11 + akt12 + akt13)*100, 2) END, '990.99') as \"%>21\"
         
         from(" . sqlSouhrnnyReport($jmeno_tab) . ")";
}


  function sqlSouhrnnyReport($jmeno_tab) {
  return "select distinct t.zprac,
                jmena.prijmeni as prijmeni,
                nvl(a.vs, 0) as vs,
                nvl(b.web, 0) as web,
                nvl(c.pdf, 0) as pdf,
                nvl(d.ostatni, 0) as ostatni,
                nvl(e.celkem, 0) as celkem,
                to_char(CASE WHEN nvl(a.vs, 0)=0 THEN 0 ELSE round(nvl(e.celkem, 0)/a.vs * 100, 2) END, '990.99') as \"% VS\",
                'doplnit' as \"nestaz D-W\",
                nvl(h.eqa3, 0) as eqa3,
                nvl(i.eqa4, 0) as eqa4,
                nvl(j.eqa5, 0) as eqa5,
                nvl(k.eqa6, 0) as eqa6,
                to_char(CASE WHEN nvl(h.eqa3, 0)+nvl(l.celkem, 0)=0 THEN 0 ELSE round(nvl(l.celkem, 0) / (nvl(h.eqa3,0)+nvl(l.celkem,0)) * 100, 2) END, '990.99') as \"%>3\",
                nvl(akt00.pocet, 0) as akt00,
                nvl(akt11.pocet, 0) as akt11,
                nvl(akt12.pocet, 0) as akt12,
                nvl(akt13.pocet, 0) as akt13,
                nvl(akt21.pocet, 0) as akt21,
                nvl(akt22.pocet, 0) as akt22,
                nvl(akt24.pocet, 0) as akt24,
                nvl(akt25.pocet, 0) as akt25,
                nvl(akt26.pocet, 0) as akt26,
                nvl(akt27.pocet, 0) as akt27,
                nvl(akt31.pocet, 0) as akt31,
                nvl(akt32.pocet, 0) as akt32,
                nvl(akt33.pocet, 0) as akt33,
                nvl(akt36.pocet, 0) as akt36,
                nvl(akt37.pocet, 0) as akt37,
                nvl(akt38.pocet, 0) as akt38,
                to_char(CASE WHEN nvl(lte_21.pocet, 0)=0 THEN 0 ELSE round(nvl(ht_21.pocet, 0)/lte_21.pocet * 100, 2) END, '990.99')
                
                
from
dante." . $jmeno_tab . " t
left join
(select zprac, listagg (prijmeni, ', ') within group (order by prijmeni) as prijmeni from 
  (select distinct zprac, substr(x_zprac, 0, instr(x_zprac, ' ')-1) as prijmeni from dante." . $jmeno_tab . ")
group by zprac) jmena
on t.zprac=jmena.zprac
left join
(select zprac, count(*) as vs from dante." . $jmeno_tab . " group by zprac) a
on t.zprac=a.zprac
left join
(select zprac, count(*) as web from dante." . $jmeno_tab . " where epv='2921.A' and sber='1160.1' group by zprac) b
on t.zprac=b.zprac
left join
(select zprac, count(*) as pdf from dante." . $jmeno_tab . " where epv='2921.P' and sber='1160.1' group by zprac) c
on t.zprac=c.zprac
left join
(select zprac, count(*) as ostatni from dante." . $jmeno_tab . " where epv not in ('2921.A', '2921.P') and sber='1160.1' group by zprac) d
on t.zprac=d.zprac
left join
(select zprac, count(*) as celkem from dante." . $jmeno_tab . " where sber='1160.1' group by zprac) e
on t.zprac=e.zprac
left join
(select zprac, count(*) as eqa3 from dante." . $jmeno_tab . " where eqa=3 group by zprac) h
on t.zprac=h.zprac
left join
(select zprac, count(*) as eqa4 from dante." . $jmeno_tab . " where eqa=4 group by zprac) i
on t.zprac=i.zprac
left join
(select zprac, count(*) as eqa5 from dante." . $jmeno_tab . " where eqa=5 group by zprac) j
on t.zprac=j.zprac
left join
(select zprac, count(*) as eqa6 from dante." . $jmeno_tab . " where eqa=6 group by zprac) k
on t.zprac=k.zprac
left join
(select zprac, count(*) as celkem from dante." . $jmeno_tab . " where eqa in (4, 5, 6) group by zprac) l
on t.zprac=l.zprac
left join
(select zprac, count(*) as pocet from dante." . $jmeno_tab . " where akt like '%00' group by zprac) akt00
on t.zprac=akt00.zprac
left join
(select zprac, count(*) as pocet from dante." . $jmeno_tab . " where akt like '%11' group by zprac) akt11
on t.zprac=akt11.zprac
left join
(select zprac, count(*) as pocet from dante." . $jmeno_tab . " where akt like '%12' group by zprac) akt12
on t.zprac=akt12.zprac
left join
(select zprac, count(*) as pocet from dante." . $jmeno_tab . " where akt like '%13' group by zprac) akt13
on t.zprac=akt13.zprac
left join
(select zprac, count(*) as pocet from dante." . $jmeno_tab . " where akt like '%21' group by zprac) akt21
on t.zprac=akt21.zprac
left join
(select zprac, count(*) as pocet from dante." . $jmeno_tab . " where akt like '%22' group by zprac) akt22
on t.zprac=akt22.zprac
left join
(select zprac, count(*) as pocet from dante." . $jmeno_tab . " where akt like '%24' group by zprac) akt24
on t.zprac=akt24.zprac
left join
(select zprac, count(*) as pocet from dante." . $jmeno_tab . " where akt like '%25' group by zprac) akt25
on t.zprac=akt25.zprac
left join
(select zprac, count(*) as pocet from dante." . $jmeno_tab . " where akt like '%26' group by zprac) akt26
on t.zprac=akt26.zprac
left join
(select zprac, count(*) as pocet from dante." . $jmeno_tab . " where akt like '%27' group by zprac) akt27
on t.zprac=akt27.zprac
left join
(select zprac, count(*) as pocet from dante." . $jmeno_tab . " where akt like '%31' group by zprac) akt31
on t.zprac=akt31.zprac
left join
(select zprac, count(*) as pocet from dante." . $jmeno_tab . " where akt like '%32' group by zprac) akt32
on t.zprac=akt32.zprac
left join
(select zprac, count(*) as pocet from dante." . $jmeno_tab . " where akt like '%33' group by zprac) akt33
on t.zprac=akt33.zprac
left join
(select zprac, count(*) as pocet from dante." . $jmeno_tab . " where akt like '%36' group by zprac) akt36
on t.zprac=akt36.zprac
left join
(select zprac, count(*) as pocet from dante." . $jmeno_tab . " where akt like '%37' group by zprac) akt37
on t.zprac=akt37.zprac
left join
(select zprac, count(*) as pocet from dante." . $jmeno_tab . " where akt like '%38' group by zprac) akt38
on t.zprac=akt38.zprac
left join
(select zprac, count(*) as pocet from dante." . $jmeno_tab . "
  where akt not like '%00'
        and akt not like '%11'
        and akt not like '%12'
        and akt not like '%13'
        and akt not like '%21'
        group by zprac) ht_21 --higher than 21
on t.zprac=ht_21.zprac
left join
(select zprac, count(*) as pocet from dante." . $jmeno_tab . "
  where akt like '%00'
  or akt like '%11'
  or akt like '%12'
  or akt like '%13'
  or akt like '%21'
  group by zprac) lte_21 --lower than or equals
on t.zprac=lte_21.zprac
order by t.zprac";
}
?>