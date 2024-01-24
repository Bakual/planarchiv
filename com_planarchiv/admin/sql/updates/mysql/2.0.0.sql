ALTER TABLE `#__planarchiv_plan`
MODIFY `stockwerk_id` int NULL,
MODIFY `richtung_didok_id` int NULL,
MODIFY `zurzeitbei_id` int NULL,
MODIFY `ersteller_id` int NULL,
MODIFY `files` varchar(50) NOT NULL DEFAULT '',
DROP COLUMN `PlanNr`,
DROP COLUMN `PlanErsteller`,
DROP COLUMN `Ort`,
DROP COLUMN `Stockwerk`,
DROP COLUMN `GebDfaCode`,
DROP COLUMN `GebDfaTxt`,
DROP COLUMN `inRichtung`,
DROP COLUMN `AnlageTyp`,
DROP COLUMN `AnlageTypTxt`,
DROP COLUMN `DokuTypCode`,
DROP COLUMN `DokuTypTxt`,
DROP COLUMN `ZurZeitBei`,
DROP COLUMN `Seit`,
DROP COLUMN `Ablage`,
DROP COLUMN `AblageSeit`;

INSERT INTO `#__content_types` (`type_title`, `type_alias`, `table`, `rules`, `field_mappings`, `router`,
                                `content_history_options`)
VALUES ('Planarchiv Plan',
        'com_planarchiv.plan',
        '{"special":{"dbtable":"#__planarchiv_plan","key":"id","type":"Plan","prefix":"PlanarchivTable","config":"array()"}}',
        '',
        '{
            "common":{
                "core_content_item_id":"id",
                "core_title":"title",
                "core_state":"state",
                "core_created_time":"created",
                "core_modified_time":"modified",
                "core_body":"bemerkungen",
                "core_version":"version"
                "core_catid":"catid"
            },
            "special":{}
        }',
        'SichtweitenHelperRoute::getLocationRoute',
        '{
        "formFile":"components\/com_planarchiv\/models\/forms\/plan.xml",
        "hideFields":["checked_out","checked_out_time","version"],
        "ignoreChanges":["checked_out", "checked_out_time", "path"],
        "convertToInt":[],
        "displayLookup":[
               {"sourceColumn":"anlagetyp_id","targetTable":"#__planarchiv_anlagetyp","targetColumn":"id","displayColumn":"title_de"},
               {"sourceColumn":"dfa_id","targetTable":"#__planarchiv_dfa","targetColumn":"id","displayColumn":"title_de"},
               {"sourceColumn":"didok_id","targetTable":"#__planarchiv_didok","targetColumn":"id","displayColumn":"title"},
               {"sourceColumn":"richtung_didok_id","targetTable":"#__planarchiv_didok","targetColumn":"id","displayColumn":"title"},
               {"sourceColumn":"dokutyp_id","targetTable":"#__planarchiv_dokutyp","targetColumn":"id","displayColumn":"title_de"},
               {"sourceColumn":"stockwerk_id","targetTable":"#__planarchiv_stockwerk","targetColumn":"id","displayColumn":"title"},
               {"sourceColumn":"ersteller_id","targetTable":"#__contact_details","targetColumn":"id","displayColumn":"name"},
               {"sourceColumn":"zurzeitbei_id","targetTable":"#__contact_details","targetColumn":"id","displayColumn":"name"},
               {"sourceColumn":"created_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"},
               {"sourceColumn":"modified_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"},
               {"sourceColumn":"catid","targetTable":"#__categories","targetColumn":"id","displayColumn":"title"}
       ]
   }');
