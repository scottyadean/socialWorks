<?php
class Main_Forms_DrugManufacturers {
    
    
    public static function names($startingWith = false){
    
        $names = array( "A" => array(
                        array (  "url" => "/manufacturer/aaipharma-1.html", "name" => "AAIPharma"),
                        array (  "url" => "/manufacturer/abbott-laboratories-2.html", "name" => "Abbott Laboratories"),
                        array (  "url" => "/manufacturer/accredo-health-inc-3.html", "name" => "Accredo Health, Inc."),
                        array (  "url" => "/manufacturer/acorda-therapeutics-inc-4.html", "name" => "Acorda Therapeutics, Inc"),
                        array (  "url" => "/manufacturer/actavis-5.html", "name" => "Actavis"),
                        array (  "url" => "/manufacturer/acura-pharmaceuticals-inc-6.html", "name" => "Acura Pharmaceuticals, Inc."),
                        array (  "url" => "/manufacturer/acusphere-inc-7.html", "name" => "Acusphere, Inc."),
                        array (  "url" => "/manufacturer/adeona-pharmaceuticals-inc-8.html", "name" => "Adeona Pharmaceuticals, Inc.,"),
                        array (  "url" => "/manufacturer/adolor-corporation-9.html", "name" => "Adolor Corporation"),
                        array (  "url" => "/manufacturer/advanced-life-sciences-holdings-inc-10.html", "name" => "Advanced Life Sciences Holdings, Inc"),
                        array (  "url" => "/manufacturer/akorn-inc-11.html", "name" => "Akorn, Inc."),
                        array (  "url" => "/manufacturer/alcon-laboratories-inc-12.html", "name" => "Alcon Laboratories, Inc."),
                        array (  "url" => "/manufacturer/alfacell-corporation-13.html", "name" => "Alfacell Corporation"),
                        array (  "url" => "/manufacturer/alkermes-174.html", "name" => "Alkermes"),
                        array (  "url" => "/manufacturer/allergan-inc-14.html", "name" => "Allergan, Inc"),
                        array (  "url" => "/manufacturer/alliance-pharmaceutical-inc-15.html", "name" => "Alliance Pharmaceutical, Inc. "),
                        array (  "url" => "/manufacturer/amag-pharmaceuticals-inc-17.html", "name" => "AMAG Pharmaceuticals, Inc"),
                        array (  "url" => "/manufacturer/amgen-inc-18.html", "name" => "Amgen Inc."),
                        array (  "url" => "/manufacturer/amneal-pharmaceuticals-19.html", "name" => "Amneal Pharmaceuticals"),
                        array (  "url" => "/manufacturer/amylin-pharmaceuticals-inc-20.html", "name" => "Amylin Pharmaceuticals, Inc. "),
                        array (  "url" => "/manufacturer/antigenics-inc-21.html", "name" => "Antigenics Inc."),
                        array (  "url" => "/manufacturer/apotex-corp-22.html", "name" => "Apotex Corp"),
                        array (  "url" => "/manufacturer/arca-biopharma-inc-23.html", "name" => "ARCA biopharma, Inc."),
                        array (  "url" => "/manufacturer/astellas-pharma-us-24.html", "name" => "Astellas Pharma US"),
                        array (  "url" => "/manufacturer/astrazeneca-pharmaceuticals-25.html", "name" => "AstraZeneca Pharmaceuticals"),
                        array (  "url" => "/manufacturer/atley-pharmaceuticals-303.html", "name" => "Atley Pharmaceuticals"),
                        array (  "url" => "/manufacturer/axcan-pharma-175.html", "name" => "Axcan Pharma" )),
                        "B" => array(
                        array (  "url" => "/manufacturer/bausch-lomb-inc-27.html", "name" => "Bausch &amp; Lomb Inc."),
                        array (  "url" => "/manufacturer/baxter-international-inc-26.html", "name" => "Baxter International Inc"),
                        array (  "url" => "/manufacturer/bayer-healthcare-pharmaceuticals-28.html", "name" => "Bayer Healthcare Pharmaceuticals"),
                        array (  "url" => "/manufacturer/biodelivery-sciences-international-inc-29.html", "name" => "BioDelivery Sciences International, Inc."),
                        array (  "url" => "/manufacturer/biogen-idec-30.html", "name" => "Biogen Idec"),
                        array (  "url" => "/manufacturer/biovail-corporation-has-merged-with-valeant-pharmaceuticals-172.html", "name" => "Biovail Corporation (has merged with Valeant Pharmaceuticals) "),
                        array (  "url" => "/manufacturer/boehringer-ingelheim-pharmaceuticals-inc-31.html", "name" => "Boehringer Ingelheim Pharmaceuticals, Inc"),
                        array (  "url" => "/manufacturer/breckenridge-pharmaceutical-inc-32.html", "name" => "Breckenridge Pharmaceutical, Inc"),
                        array (  "url" => "/manufacturer/bristol-myers-squibb-company-33.html", "name" => "Bristol-Myers Squibb Company")), 
                        "C" => array(
                        array (  "url" => "/manufacturer/capellon-pharmaceuticals-295.html", "name" => "Capellon Pharmaceuticals"),
                        array (  "url" => "/manufacturer/caraco-pharmaceutical-laboratories-34.html", "name" => "Caraco Pharmaceutical Laboratories"),
                        array (  "url" => "/manufacturer/celgene-corporation-170.html", "name" => "Celgene Corporation"),
                        array (  "url" => "/manufacturer/centocor-ortho-biotech-inc-35.html", "name" => "Centocor Ortho Biotech, Inc"),
                        array (  "url" => "/manufacturer/cephalon-inc-36.html", "name" => "Cephalon, Inc."),
                        array (  "url" => "/manufacturer/chugai-pharma-usa-llc-37.html", "name" => "Chugai Pharma USA, LLC"),
                        array (  "url" => "/manufacturer/cobalt-laboratories-38.html", "name" => "Cobalt Laboratories"),
                        array (  "url" => "/manufacturer/collegium-pharmaceutical-inc-39.html", "name" => "Collegium Pharmaceutical, Inc."),
                        array (  "url" => "/manufacturer/csl-behring-40.html", "name" => "CSL Behring "),
                        array (  "url" => "/manufacturer/cypress-bioscience-inc-41.html", "name" => "Cypress Bioscience, Inc."),
                        array (  "url" => "/manufacturer/cypress-pharmaceuticals-279.html", "name" => "Cypress Pharmaceuticals")), 
                        "D" =>  array(
                        array (  "url" => "/manufacturer/daiichi-sankyo-165.html", "name" => "Daiichi Sankyo"),
                        array (  "url" => "/manufacturer/dava-pharmaceuticals-inc-42.html", "name" => "DAVA Pharmaceuticals, Inc."),
                        array (  "url" => "/manufacturer/dey-pharma-43.html", "name" => "Dey Pharma"),
                        array (  "url" => "/manufacturer/digestive-care-inc-44.html", "name" => "Digestive Care, Inc"),
                        array (  "url" => "/manufacturer/discovery-laboratories-inc-45.html", "name" => "Discovery Laboratories, Inc."),
                        array (  "url" => "/manufacturer/dor-biopharma-inc-46.html", "name" => "DOR BioPharma, Inc."),
                        array (  "url" => "/manufacturer/dr-reddy-s-laboratories-inc-47.html", "name" => "Dr. Reddy's Laboratories Inc."),
                        array (  "url" => "/manufacturer/dyax-corp-48.html", "name" => "Dyax Corp")), 
                        "E" =>  array(
                        array (  "url" => "/manufacturer/eisai-corporation-49.html", "name" => "Eisai Corporation "),
                        array (  "url" => "/manufacturer/elan-corporation-50.html", "name" => "Elan Corporation"),
                        array (  "url" => "/manufacturer/eli-lilly-and-company-81.html", "name" => "Eli Lilly and Company"),
                        array (  "url" => "/manufacturer/elorac-inc-182.html", "name" => "Elorac, Inc"),
                        array (  "url" => "/manufacturer/endo-pharmaceuticals-51.html", "name" => "Endo Pharmaceuticals"),
                        array (  "url" => "/manufacturer/epicept-corporation-52.html", "name" => "EpiCept Corporation"),
                        array (  "url" => "/manufacturer/epix-pharmaceuticals-inc-53.html", "name" => "EPIX Pharmaceuticals, Inc."),
                        array (  "url" => "/manufacturer/ethex-corporation-54.html", "name" => "Ethex Corporation"),
                        array (  "url" => "/manufacturer/eusa-pharma-55.html", "name" => "Eusa Pharma")), 
                        "F" =>  array(
                        array (  "url" => "/manufacturer/ferring-pharmaceuticals-176.html", "name" => "Ferring Pharmaceuticals"),
                        array (  "url" => "/manufacturer/forest-pharmaceuticals-inc-56.html", "name" => "Forest Pharmaceuticals, Inc"),
                        array (  "url" => "/manufacturer/fresenius-medical-care-183.html", "name" => "Fresenius Medical Care")), 
                        "G" =>  array(
                        array (  "url" => "/manufacturer/galderma-laboratories-171.html", "name" => "Galderma Laboratories"),
                        array (  "url" => "/manufacturer/genentech-inc-57.html", "name" => "Genentech, Inc"),
                        array (  "url" => "/manufacturer/genta-incorporated-58.html", "name" => "Genta Incorporated"),
                        array (  "url" => "/manufacturer/genzyme-corporation-59.html", "name" => "Genzyme Corporation"),
                        array (  "url" => "/manufacturer/geron-corporation-60.html", "name" => "Geron Corporation "),
                        array (  "url" => "/manufacturer/gilead-sciences-inc-61.html", "name" => "Gilead Sciences, Inc. "),
                        array (  "url" => "/manufacturer/glaxosmithkline-62.html", "name" => "GlaxoSmithKline"),
                        array (  "url" => "/manufacturer/global-pharmaceuticals-63.html", "name" => "Global Pharmaceuticals"),
                        array (  "url" => "/manufacturer/graceway-pharmaceuticals-llc-166.html", "name" => "Graceway Pharmaceuticals, LLC"),
                        array (  "url" => "/manufacturer/greenstone-llc-64.html", "name" => "Greenstone LLC"),
                        array (  "url" => "/manufacturer/gtc-biotherapeutics-inc-65.html", "name" => "GTC Biotherapeutics, Inc."),
                        array (  "url" => "/manufacturer/gtx-inc-66.html", "name" => "GTx, Inc.")), 
                        "H" =>  array(
                        array (  "url" => "/manufacturer/helix-biopharma-corp-67.html", "name" => "Helix BioPharma Corp"),
                        array (  "url" => "/manufacturer/hemispherx-biopharma-inc-156.html", "name" => "Hemispherx Biopharma, Inc."),
                        array (  "url" => "/manufacturer/heritage-pharmaceuticals-188.html", "name" => "Heritage Pharmaceuticals"),
                        array (  "url" => "/manufacturer/hospira-177.html", "name" => "Hospira")), 
                        "I" =>  array(
                        array (  "url" => "/manufacturer/imclone-systems-incorporated-68.html", "name" => "ImClone Systems Incorporated "),
                        array (  "url" => "/manufacturer/impax-laboratories-inc-69.html", "name" => "Impax Laboratories, Inc."),
                        array (  "url" => "/manufacturer/incyte-corporation-70.html", "name" => "Incyte Corporation"),
                        array (  "url" => "/manufacturer/interpharm-holdings-inc-71.html", "name" => "Interpharm Holdings, Inc"),
                        array (  "url" => "/manufacturer/isis-pharmaceuticals-inc-72.html", "name" => "Isis Pharmaceuticals, Inc."),
                        array (  "url" => "/manufacturer/ista-pharmaceuticals-inc-73.html", "name" => "ISTA Pharmaceuticals, Inc.")), 
                        "J" =>  array(
                        array (  "url" => "/manufacturer/janssen-pharmaceuticals-inc-74.html", "name" => "Janssen Pharmaceuticals, Inc"),
                        array (  "url" => "/manufacturer/johnson-johnson-75.html", "name" => "Johnson &amp; Johnson")), 
                        "K" =>  array(
                        array (  "url" => "/manufacturer/k-v-pharmaceutical-company-77.html", "name" => "K-V Pharmaceutical Company"),
                        array (  "url" => "/manufacturer/king-pharmaceuticals-inc-now-part-of-pfizer-october-2010-76.html", "name" => "King Pharmaceuticals, Inc. (now part of Pfizer - October 2010)"),
                        array (  "url" => "/manufacturer/kremers-urban-pharmaceuticals-inc-282.html", "name" => "Kremers Urban Pharmaceuticals Inc."),
                        array (  "url" => "/manufacturer/kvk-tech-inc-78.html", "name" => "KVK-TECH, Inc."),
                        array (  "url" => "/manufacturer/kyowa-pharmaceutical-inc-79.html", "name" => "Kyowa Pharmaceutical, Inc. ")), 
                        "L" =>  array(
                        array (  "url" => "/manufacturer/lannett-company-inc-80.html", "name" => "Lannett Company, Inc"),
                        array (  "url" => "/manufacturer/luitpold-pharmaceuticals-inc-82.html", "name" => "Luitpold Pharmaceuticals, Inc"),
                        array (  "url" => "/manufacturer/lundbeck-inc-83.html", "name" => "Lundbeck Inc."),
                        array (  "url" => "/manufacturer/lupin-pharmaceuticals-inc-84.html", "name" => "Lupin Pharmaceuticals, Inc")),
                        "M" =>  array(
                        array (  "url" => "/manufacturer/major-pharmaceuticals-189.html", "name" => "Major Pharmaceuticals"),
                        array (  "url" => "/manufacturer/mallinckrodt-inc-85.html", "name" => "Mallinckrodt Inc."),
                        array (  "url" => "/manufacturer/martek-biosciences-company-86.html", "name" => "Martek Biosciences Company"),
                        array (  "url" => "/manufacturer/mcneil-consumer-healthcare-481.html", "name" => "McNeil Consumer Healthcare"),
                        array (  "url" => "/manufacturer/mcr-american-pharmaceuticals-inc-280.html", "name" => "MCR American Pharmaceuticals Inc"),
                        array (  "url" => "/manufacturer/me-pharmaceuticals-161.html", "name" => "ME Pharmaceuticals"),
                        array (  "url" => "/manufacturer/meda-pharmaceuticals-87.html", "name" => "Meda Pharmaceuticals"),
                        array (  "url" => "/manufacturer/medicis-pharmaceutical-corporation-88.html", "name" => "Medicis Pharmaceutical Corporation"),
                        array (  "url" => "/manufacturer/medimmune-inc-90.html", "name" => "MedImmune, Inc"),
                        array (  "url" => "/manufacturer/mediquest-therapeutics-inc-89.html", "name" => "MediQuest Therapeutics Inc "),
                        array (  "url" => "/manufacturer/merck-co-inc-91.html", "name" => "Merck &amp; Co., Inc."),
                        array (  "url" => "/manufacturer/morton-grove-pharmaceuticals-inc-92.html", "name" => "Morton Grove Pharmaceuticals, Inc"),
                        array (  "url" => "/manufacturer/mylan-inc-93.html", "name" => "Mylan Inc.")), 
                        "N" =>  array(
                        array (  "url" => "/manufacturer/nagase-america-corp-94.html", "name" => "Nagase America Corp."),
                        array (  "url" => "/manufacturer/neurocrine-biosciences-inc-95.html", "name" => "Neurocrine Biosciences, Inc."),
                        array (  "url" => "/manufacturer/neurogesx-inc-96.html", "name" => "NeurogesX, Inc"),
                        array (  "url" => "/manufacturer/nexmed-inc-97.html", "name" => "NexMed, Inc"),
                        array (  "url" => "/manufacturer/northfield-laboratories-inc-98.html", "name" => "Northfield Laboratories Inc."),
                        array (  "url" => "/manufacturer/northstar-rx-187.html", "name" => "Northstar Rx"),
                        array (  "url" => "/manufacturer/novadel-pharma-inc-99.html", "name" => "NovaDel Pharma, Inc."),
                        array (  "url" => "/manufacturer/novartis-corporation-100.html", "name" => "Novartis Corporation"),
                        array (  "url" => "/manufacturer/noven-pharmaceuticals-inc-101.html", "name" => "Noven Pharmaceuticals, Inc."),
                        array (  "url" => "/manufacturer/novo-nordisk-inc-102.html", "name" => "Novo Nordisk Inc."),
                        array (  "url" => "/manufacturer/nycomed-173.html", "name" => "Nycomed")), 
                        "O" =>   array(
                        array (  "url" => "/manufacturer/oncogenex-pharmaceuticals-103.html", "name" => "OncoGenex Pharmaceuticals "),
                        array (  "url" => "/manufacturer/onyx-pharmaceuticals-inc-104.html", "name" => "Onyx Pharmaceuticals, Inc"),
                        array (  "url" => "/manufacturer/ortho-mcneil-janssen-pharmaceuticals-inc-105.html", "name" => "Ortho-McNeil-Janssen Pharmaceuticals, Inc."),
                        array (  "url" => "/manufacturer/osiris-therapeutics-inc-106.html", "name" => "Osiris Therapeutics, Inc."),
                        array (  "url" => "/manufacturer/othera-pharmaceuticals-inc-107.html", "name" => "Othera Pharmaceuticals, Inc"),
                        array (  "url" => "/manufacturer/otsuka-pharmaceutical-co-178.html", "name" => "Otsuka Pharmaceutical Co.")), 
                        "P" =>  array(
                        array (  "url" => "/manufacturer/paddock-laboratories-inc-108.html", "name" => "Paddock Laboratories, Inc., "),
                        array (  "url" => "/manufacturer/pain-therapeutics-inc-109.html", "name" => "Pain Therapeutics, Inc."),
                        array (  "url" => "/manufacturer/par-pharmaceutical-110.html", "name" => "Par Pharmaceutical"),
                        array (  "url" => "/manufacturer/pdl-biopharma-inc-111.html", "name" => "PDL BioPharma, Inc. "),
                        array (  "url" => "/manufacturer/perrigo-company-112.html", "name" => "Perrigo Company "),
                        array (  "url" => "/manufacturer/pfizer-inc-113.html", "name" => "Pfizer Inc"),
                        array (  "url" => "/manufacturer/pharmacyclics-inc-114.html", "name" => "Pharmacyclics, Inc."),
                        array (  "url" => "/manufacturer/pierre-fabre-pharmaceuticals-inc-184.html", "name" => "Pierre Fabre Pharmaceuticals Inc"),
                        array (  "url" => "/manufacturer/pliva-179.html", "name" => "Pliva"),
                        array (  "url" => "/manufacturer/pozen-inc-115.html", "name" => "Pozen Inc"),
                        array (  "url" => "/manufacturer/prasco-laboratories-190.html", "name" => "Prasco Laboratories"),
                        array (  "url" => "/manufacturer/pro-pharmaceuticals-inc-118.html", "name" => "Pro-Pharmaceuticals, Inc."),
                        array (  "url" => "/manufacturer/procter-gamble-116.html", "name" => "Procter &amp; Gamble"),
                        array (  "url" => "/manufacturer/progenics-pharmaceuticals-inc-117.html", "name" => "Progenics Pharmaceuticals, Inc."),
                        array (  "url" => "/manufacturer/purdue-pharma-lp-160.html", "name" => "Purdue Pharma LP")), 
                        "Q" =>  array(
                        array (  "url" => "/manufacturer/qualitest-pharmaceuticals-119.html", "name" => "Qualitest Pharmaceuticals" )),
                        "R" => array( 
                        array (  "url" => "/manufacturer/ranbaxy-pharmaceuticals-inc-120.html", "name" => "Ranbaxy Pharmaceuticals Inc."),
                        array (  "url" => "/manufacturer/reckitt-benckiser-pharmaceuticals-inc-162.html", "name" => "Reckitt Benckiser Pharmaceuticals Inc."),
                        array (  "url" => "/manufacturer/roche-pharmaceuticals-121.html", "name" => "Roche Pharmaceuticals "),
                        array (  "url" => "/manufacturer/roxane-laboratories-inc-122.html", "name" => "Roxane Laboratories,Inc."),
                        array (  "url" => "/manufacturer/roxro-pharma-inc-123.html", "name" => "Roxro Pharma, Inc.")), 
                        "S" =>  array(
                        array (  "url" => "/manufacturer/salix-pharmaceuticals-ltd-124.html", "name" => "Salix Pharmaceuticals, Ltd"),
                        array (  "url" => "/manufacturer/sandoz-inc-125.html", "name" => "Sandoz Inc."),
                        array (  "url" => "/manufacturer/sanofi-aventis-126.html", "name" => "Sanofi-Aventis"),
                        array (  "url" => "/manufacturer/santarus-inc-169.html", "name" => "Santarus, Inc."),
                        array (  "url" => "/manufacturer/savient-pharmaceuticals-inc-127.html", "name" => "Savient Pharmaceuticals, Inc."),
                        array (  "url" => "/manufacturer/schering-plough-128.html", "name" => "Schering-Plough"),
                        array (  "url" => "/manufacturer/sepracor-inc-renamed-sunovion-pharmaceuticals-inc-163.html", "name" => "Sepracor Inc. (renamed Sunovion Pharmaceuticals Inc)"),
                        array (  "url" => "/manufacturer/shionogi-pharma-inc-129.html", "name" => "Shionogi Pharma, Inc"),
                        array (  "url" => "/manufacturer/shire-us-inc-164.html", "name" => "Shire US Inc"),
                        array (  "url" => "/manufacturer/siga-technologies-inc-130.html", "name" => "SIGA Technologies, Inc."),
                        array (  "url" => "/manufacturer/sigma-tau-pharmaceuticals-180.html", "name" => "Sigma-Tau Pharmaceuticals"),
                        array (  "url" => "/manufacturer/sirion-therapeutics-inc-131.html", "name" => "Sirion Therapeutics, Inc. "),
                        array (  "url" => "/manufacturer/solvay-pharmaceuticals-inc-132.html", "name" => "Solvay Pharmaceuticals, Inc."),
                        array (  "url" => "/manufacturer/somaxon-pharmaceuticals-inc-133.html", "name" => "Somaxon Pharmaceuticals, Inc."),
                        array (  "url" => "/manufacturer/spherix-incorporated-134.html", "name" => "Spherix Incorporated"),
                        array (  "url" => "/manufacturer/stiefel-laboratories-inc-168.html", "name" => "Stiefel Laboratories, Inc."),
                        array (  "url" => "/manufacturer/summers-laboratories-135.html", "name" => "Summers Laboratories "),
                        array (  "url" => "/manufacturer/sunovion-pharmaceuticals-inc-276.html", "name" => "Sunovion Pharmaceuticals Inc."),
                        array (  "url" => "/manufacturer/supergen-inc-136.html", "name" => "SuperGen, Inc.,"),
                        array (  "url" => "/manufacturer/synthon-pharmaceuticals-ltd-296.html", "name" => "Synthon Pharmaceuticals Ltd")), 
                        "T" => array(
                        array (  "url" => "/manufacturer/takeda-pharmaceuticals-north-america-inc-137.html", "name" => "Takeda Pharmaceuticals North America, Inc"),
                        array (  "url" => "/manufacturer/targanta-therapeutics-corporation-138.html", "name" => "Targanta Therapeutics Corporation"),
                        array (  "url" => "/manufacturer/taro-pharmaceuticals-u-s-a-inc-139.html", "name" => "Taro Pharmaceuticals U.S.A., Inc."),
                        array (  "url" => "/manufacturer/teva-pharmaceuticals-140.html", "name" => "Teva Pharmaceuticals "),
                        array (  "url" => "/manufacturer/ther-rx-corporation-300.html", "name" => "Ther-Rx Corporation"),
                        array (  "url" => "/manufacturer/theravance-inc-141.html", "name" => "Theravance, Inc."),
                        array (  "url" => "/manufacturer/three-rivers-pharmaceuticals-llc-143.html", "name" => "Three Rivers Pharmaceuticals, LLC "),
                        array (  "url" => "/manufacturer/torrent-pharmaceuticals-181.html", "name" => "Torrent Pharmaceuticals"),
                        array (  "url" => "/manufacturer/transcept-pharmaceuticals-inc-142.html", "name" => "Transcept Pharmaceuticals, Inc.")), 
                        "U" =>  array(
                        array (  "url" => "/manufacturer/ucb-inc-144.html", "name" => "UCB Inc."),
                        array (  "url" => "/manufacturer/upsher-smith-laboratories-inc-145.html", "name" => "Upsher-Smith Laboratories Inc."),
                        array (  "url" => "/manufacturer/url-pharma-inc-now-sun-pharma-146.html", "name" => "URL Pharma, Inc.(now Sun Pharma)")), 
                        "V" => array(
                        array (  "url" => "/manufacturer/valeant-pharmaceuticals-international-147.html", "name" => "Valeant Pharmaceuticals International"),
                        array (  "url" => "/manufacturer/vanda-pharmaceuticals-inc-148.html", "name" => "Vanda Pharmaceuticals Inc."),
                        array (  "url" => "/manufacturer/versapharm-inc-274.html", "name" => "VersaPharm Inc"),
                        array (  "url" => "/manufacturer/vertex-pharmaceuticals-149.html", "name" => "Vertex Pharmaceuticals"),
                        array (  "url" => "/manufacturer/viropharma-185.html", "name" => "ViroPharma")), 
                        "W" => array(
                        array (  "url" => "/manufacturer/warner-chilcott-167.html", "name" => "Warner Chilcott"),
                        array (  "url" => "/manufacturer/watson-pharmaceuticals-inc-now-actavis-inc-150.html", "name" => "Watson Pharmaceuticals, Inc. (now Actavis, Inc)"),
                        array (  "url" => "/manufacturer/west-ward-pharmaceuticals-186.html", "name" => "West-Ward Pharmaceuticals"),
                        array (  "url" => "/manufacturer/wockhardt-usa-151.html", "name" => "Wockhardt USA "),
                        array (  "url" => "/manufacturer/wyeth-159.html", "name" => "Wyeth")), 
                        
                        "X" => array(
                        array (  "url" => "/manufacturer/xanodyne-pharmaceuticals-inc-152.html", "name" => "Xanodyne Pharmaceuticals, Inc"),
                        array (  "url" => "/manufacturer/xenoport-inc-153.html", "name" => "XenoPort, Inc")), 
                        
                        "Z" =>  array(
                        array (  "url" => "/manufacturer/zila-inc-154.html", "name" => "Zila, Inc."),
                        array (  "url" => "/manufacturer/zogenix-inc-155.html", "name" => "Zogenix, Inc"),
                        array (  "url" => "/manufacturer/zydus-pharmaceuticals-157.html", "name" => "Zydus Pharmaceuticals")),);
        
        
        
        if( $startingWith != false ) {
            
            
            $names = isset( $names[$startingWith] ) ? $names[$startingWith] : $names;
            
        }
        
        return $names;
    
    }
    
}    
        
  