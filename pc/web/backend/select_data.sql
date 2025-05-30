-- slect donnees pour avoir count des enregistrements et statistiques
SELECT
  'Campaigns' as table_name,
  COUNT(*) as record_count
FROM
  campaigns
UNION ALL
SELECT
  'Drying Cycles' as table_name,
  COUNT(*) as record_count
FROM
  drying_cycles
UNION ALL
SELECT
  'Etages' as table_name,
  COUNT(*) as record_count
FROM
  etages;

-- select donnees pour avoir count des enregistrements et statistiques par variété
SELECT
  variety_name,
  COUNT(*) as cycle_count,
  MIN(cycle_date) as first_cycle,
  MAX(cycle_date) as last_cycle
FROM
  drying_cycles dc
  JOIN campaigns c ON dc.campaign_id = c.id
  JOIN etages e ON e.cycle_id = dc.id
GROUP BY
  variety_name
ORDER BY
  variety_name;
-- select pour avoir la durée moyenne par étage et par variété
SELECT
  variety_name,
  ROUND(AVG(CASE WHEN floor_number = '1' THEN floor_duration_min ELSE NULL END) / 60, 2) AS floor_1_avg,
  ROUND(AVG(CASE WHEN floor_number = '2' THEN floor_duration_min ELSE NULL END) / 60, 2) AS floor_2_avg,
  ROUND(AVG(CASE WHEN floor_number = '3' THEN floor_duration_min ELSE NULL END) / 60, 2) AS floor_3_avg,
  ROUND(AVG(CASE WHEN floor_number = '4' THEN floor_duration_min ELSE NULL END) / 60, 2) AS floor_4_avg
  FROM etages
GROUP BY variety_name
ORDER BY variety_name;




