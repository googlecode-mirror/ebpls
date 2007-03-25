

function loadMenus() 
{
	

	
    //Report Manager -- Business Establishment By
    bizBy = new Menu("Business Establishment By...");
    bizBy.addMenuItem("Barangay", "location='index.php?part=5&frmReportType=3'");
    bizBy.addMenuItem("Business Category", "location='index.php?part=5&frmReportType=4'");
    bizBy.addMenuItem("Business Type", "location='index.php?part=5&frmReportType=5'");
    bizBy.addMenuItem("Capital Investment", "location='index.php?part=5&frmReportType=6'");
    bizBy.addMenuItem("Nature of Business", "location='index.php?part=5&frmReportType=7'");
    bizBy.addMenuItem("Owner", "location='index.php?part=5&frmReportType=8'");
    bizBy.menuItemWidth = 125;

    //Report Manager -- Business Tax
    bizTax = new Menu("Business Tax...");
    bizTax.addMenuItem("Collectibles", "location='index.php?part=5&frmReportType=11'");
    bizTax.addMenuItem("Collection", "location='index.php?part=5&frmReportType=12'");
    bizTax.addMenuItem("Delinquency", "location='index.php?part=5&frmReportType=13'");
    bizTax.menuItemWidth = 100;

    //Report Manager -- Collection Comparative
    colComp = new Menu("Collection Comparative...");
    colComp.addMenuItem("Chart", "location='index.php?part=5&frmReportType=15'");
    colComp.addMenuItem("Report", "location='index.php?part=5&frmReportType=16'");
    colComp.menuItemWidth = 90;

    //Report Manager -- Summary of Business
    sumBiz = new Menu("Summary of Business...");
    sumBiz.addMenuItem("Fees Delinquency", "location='index.php?part=5&frmReportType=23'");
    sumBiz.addMenuItem("Requirements Delinquency", "location='index.php?part=5&frmReportType=24'");
    sumBiz.addMenuItem("Tax Delinquency", "location='index.php?part=5&frmReportType=25'");
    sumBiz.menuItemWidth = 125;

    //Report Manager
    /**

    repMan = new Menu("Report Manager");
    repMan.addMenuItem("Approved Business Establishment", "location='index.php?part=5&frmReportType=1'");
    repMan.addMenuItem("Blacklisted Business Establishment", "location='index.php?part=5&frmReportType=2'");
    repMan.addMenuItem(bizBy);
    repMan.addMenuItem("Business Fees Collection", "location='index.php?part=5&frmReportType=9'");
    repMan.addMenuItem("Business Profile", "location='index.php?part=5&frmReportType=10'");
    repMan.addMenuItem(bizTax);
    repMan.addMenuItem("Closed Business Establishment", "location='index.php?part=5&frmReportType=14'");
    repMan.addMenuItem(colComp);
    repMan.addMenuItem("Detailed Requirements Delinquency", "location='index.php?part=5&frmReportType=17'");
    repMan.addMenuItem("Individual Record of Payment", "location='index.php?part=5&frmReportType=18'");
    repMan.addMenuItem("Master List Business Establishment", "location='index.php?part=5&frmReportType=19'");
    repMan.addMenuItem("Mayor's Permit Collection", "location='index.php?part=5&frmReportType=20'");
    repMan.addMenuItem("Newly Opened Business Establishment", "location='index.php?part=5&frmReportType=21'");
    repMan.addMenuItem("Pending Business Establishment", "location='index.php?part=5&frmReportType=22'");
    repMan.addMenuItem(sumBiz);
    repMan.addMenuItem("Suspended Business Establishment", "location='index.php?part=5&frmReportType=26'");
    repMan.menuItemWidth = 250; 
    **/
	
    // Community Tax Certificate
    taxCert = new Menu("Community Tax Certificate");
    taxCert.addMenuItem("Individual", "location='index.php?part=101&cmd=&ctc_type=INDIVIDUAL'");
    taxCert.addMenuItem("Business", "location='index.php?part=101&cmd=&ctc_type=BUSINESS'");
    taxCert.addMenuItem("CTC Report", "location='index.php?part=1217'");
    taxCert.menuItemWidth = 100;

    // Permit
    // Business Permit --- Application
    bizPerApp = new Menu("Application");
    bizPerApp.addMenuItem("New", "location='index.php?part=210&method_of_application=new&permit_type=business'");
    bizPerApp.addMenuItem("Renew", "location='index.php?part=210&method_of_application=renew&permit_type=business'");
    bizPerApp.addMenuItem("Retire", "location='index.php?part=210&method_of_application=retire&permit_type=business'");
    bizPerApp.addMenuItem("Search", "location='index.php?part=201'");
    bizPerApp.menuItemWidth = 100;

    // Business Permit --- Assessment
    bizPerAss = new Menu("Assessment");
    bizPerAss.addMenuItem("In-Progress", "location='index.php?part=303&method_of_application=new&permit_type=business'");
    //bizPerAss.addMenuItem("Renew", "location='index.php?part=303&method_of_application=renew&permit_type=business'");
    //bizPerAss.addMenuItem("Retire", "location='index.php?part=303&method_of_application=retire&permit_type=business'");
    bizPerAss.addMenuItem("Search", "location='index.php?part=301'");
    bizPerAss.menuItemWidth = 100;

    // Business Permit --- Payment
    bizPerPay = new Menu("Payment");
    bizPerPay.addMenuItem("Processing", "location='index.php?part=403&permit_type=business'");
    bizPerPay.addMenuItem("Search", "location='index.php?part=401'");
    bizPerPay.menuItemWidth = 100;

    // Business Permit --- Approval
    bizPerAppr = new Menu("Approval");
    bizPerAppr.addMenuItem("In-Progress", "location='index.php?part=503&method_of_application=new&permit_type=business'");
    //bizPerAppr.addMenuItem("Renew", "location='index.php?part=503&method_of_application=renew&permit_type=business'");
    //bizPerAppr.addMenuItem("Retire", "location='index.php?part=503&method_of_application=retire&permit_type=business'");
    bizPerAppr.addMenuItem("Search", "location='index.php?part=501'");
    bizPerAppr.menuItemWidth = 100;

    // Business Permit --- Releasing
    bizPerRel = new Menu("Releasing");
    bizPerRel.addMenuItem("In-Progress", "location='index.php?part=603&method_of_application=new&permit_type=business'");
    //bizPerRel.addMenuItem("Renew", "location='index.php?part=603&method_of_application=renew&permit_type=business'");
    //bizPerRel.addMenuItem("Retire", "location='index.php?part=603&method_of_application=retire&permit_type=business'");
    bizPerRel.addMenuItem("Search", "location='index.php?part=601'");
    bizPerRel.menuItemWidth = 100;

    // Business Permit
    bizPer = new Menu("Business Permit");
    bizPer.addMenuItem(bizPerApp);
    bizPer.addMenuItem(bizPerAss);
    bizPer.addMenuItem(bizPerPay);
    bizPer.addMenuItem(bizPerAppr);
    bizPer.addMenuItem(bizPerRel);
    bizPer.menuItemWidth = 100;

    // Motorized Operator Permit --- Application
    motorPerApp = new Menu("Application");
    motorPerApp.addMenuItem("New", "location='index.php?part=211&method_of_application=new&permit_type=motorized'");
    motorPerApp.addMenuItem("Renew", "location='index.php?part=211&method_of_application=renew&permit_type=motorized'");
    motorPerApp.addMenuItem("Retire", "location='index.php?part=211&method_of_application=retire&permit_type=motorized'");
    motorPerApp.addMenuItem("Search", "location='index.php?part=201'");
    motorPerApp.menuItemWidth = 100;

    // Motorized Operator Permit --- Assessment
    motorPerAss = new Menu("Assessment");
    motorPerAss.addMenuItem("In-Progress", "location='index.php?part=303&method_of_application=new&permit_type=motorized'");
    // motorPerAss.addMenuItem("Renew", "location='index.php?part=303&method_of_application=renew&permit_type=motorized'");
    // motorPerAss.addMenuItem("Retire", "location='part=303&method_of_application=retire&permit_type=motorized'");
    motorPerAss.addMenuItem("Search", "location='index.php?part=301'");
    motorPerAss.menuItemWidth = 100;

    // Motorized Operator Permit --- Payment
    motorPerPay = new Menu("Payment");
    motorPerPay.addMenuItem("Processing", "location='index.php?part=403&permit_type=motorized'");
    motorPerPay.addMenuItem("Search", "location='index.php?part=401'");
    motorPerPay.menuItemWidth = 100;

    // Motorized Operator Permit --- Approval
    motorPerAppr = new Menu("Approval");
    motorPerAppr.addMenuItem("In-Progress", "location='index.php?part=503&method_of_application=new&permit_type=motorized'");
    //motorPerAppr.addMenuItem("Renew", "location='index.php?part=503&method_of_application=renew&permit_type=motorized'");
    //motorPerAppr.addMenuItem("Retire", "location='index.php?part=503&method_of_application=retire&permit_type=motorized'");
    motorPerAppr.addMenuItem("Search", "location='index.php?part=501'");
    motorPerAppr.menuItemWidth = 100;

    // Motorized Operator Permit --- Releasing
    motorPerRel = new Menu("Releasing");
    motorPerRel.addMenuItem("In-Progress", "location='index.php?part=603&method_of_application=new&permit_type=motorized'");
    //motorPerRel.addMenuItem("Renew", "location='index.php?part=603&method_of_application=renew&permit_type=motorized'");
    //motorPerRel.addMenuItem("Retire", "location='index.php?part=603&method_of_application=retire&permit_type=motorized'");
    motorPerRel.addMenuItem("Search", "location='index.php?part=601'");
    motorPerRel.menuItemWidth = 100;

    // Motorized Operator Permit
    motorPer = new Menu("Motorized Operator Permit");
    motorPer.addMenuItem(motorPerApp);
    motorPer.addMenuItem(motorPerAss);
    motorPer.addMenuItem(motorPerPay);
    motorPer.addMenuItem(motorPerAppr);
    motorPer.addMenuItem(motorPerRel);
    motorPer.menuItemWidth = 100;

    // Occupational Permit --- Application
    occPerApp = new Menu("Application");
    occPerApp.addMenuItem("New", "location='index.php?part=212&method_of_application=new&permit_type=occupational'");
    occPerApp.addMenuItem("Renew", "location='index.php?part=212&method_of_application=renew&permit_type=occupational'");
    occPerApp.addMenuItem("Retire", "location='index.php?part=212&method_of_application=retire&permit_type=occupational'");
    occPerApp.addMenuItem("Search", "location='index.php?part=201'");
    occPerApp.menuItemWidth = 100;

    // Occupational Permit --- Assessment
    occPerAss = new Menu("Assessment");
    occPerAss.addMenuItem("In-Progress", "location='index.php?part=303&method_of_application=new&permit_type=occupational'");
    //occPerAss.addMenuItem("Renew", "location='index.php?part=303&method_of_application=renew&permit_type=occupational'");
    //occPerAss.addMenuItem("Retire", "location='index.php?part=303&method_of_application=retire&permit_type=occupational'");
    occPerAss.addMenuItem("Search", "location='index.php?part=301'");
    occPerAss.menuItemWidth = 100;

    // Occupational Permit --- Payment
    occPerPay = new Menu("Payment");
    occPerPay.addMenuItem("Processing", "location='index.php?part=403&permit_type=occupational'");
    occPerPay.addMenuItem("Search", "location='index.php?part=401'");
    occPerPay.menuItemWidth = 100;

    // Occupational Permit --- Approval
    occPerAppr = new Menu("Approval");
    occPerAppr.addMenuItem("In-Progress", "location='index.php?part=503&method_of_application=new&permit_type=occupational'");
    //occPerAppr.addMenuItem("Renew", "location='index.php?part=503&method_of_application=renew&permit_type=occupational'");
    //occPerAppr.addMenuItem("Retire", "location='index.php?part=503&method_of_application=retire&permit_type=occupational'");
    occPerAppr.addMenuItem("Search", "location='index.php?part=501'");
    occPerAppr.menuItemWidth = 100;

    // Occupational Permit --- Releasing
    occPerRel = new Menu("Releasing");
    occPerRel.addMenuItem("In-Progress", "top.window.location='index.php?part=603&method_of_application=new&permit_type=occupational'");
    //occPerRel.addMenuItem("Renew", "top.window.location='index.php?part=603&method_of_application=renew&permit_type=occupational'");
    //occPerRel.addMenuItem("Retire", "top.window.location='index.php?part=603&method_of_application=retire&permit_type=occupational'");
    occPerRel.addMenuItem("Search", "location='index.php?part=601'");
    occPerRel.menuItemWidth = 100;

    // Occupational Permit
    occPer = new Menu("Occupational Permit");
    occPer.addMenuItem(occPerApp);
    occPer.addMenuItem(occPerAss);
    occPer.addMenuItem(occPerPay);
    occPer.addMenuItem(occPerAppr);
    occPer.addMenuItem(occPerRel);
    occPer.menuItemWidth = 100;

    // Peddlers Permit --- Application
    pedPerApp = new Menu("Application");
    pedPerApp.addMenuItem("New", "location='index.php?part=213&method_of_application=new&permit_type=peddlers'");
    pedPerApp.addMenuItem("Renew", "location='index.php?part=213&method_of_application=renew&permit_type=peddlers'");
    pedPerApp.addMenuItem("Retire", "location='index.php?part=213&method_of_application=retire&permit_type=peddlers'");
    pedPerApp.addMenuItem("Search", "location='index.php?part=201'");
    pedPerApp.menuItemWidth = 100;

    // Peddlers Permit --- Assessment
    pedPerAss = new Menu("Assessment");
    pedPerAss.addMenuItem("In-Progress", "location='index.php?part=303&method_of_application=new&permit_type=peddlers'");
    //pedPerAss.addMenuItem("Renew", "location='index.php?part=303&method_of_application=renew&permit_type=peddlers'");
    //pedPerAss.addMenuItem("Retire", "location='index.php?part=303&method_of_application=retire&permit_type=peddlers'");
    pedPerAss.addMenuItem("Search", "location='index.php?part=301'");
    pedPerAss.menuItemWidth = 100;

    // Peddlers Permit --- Payment
    pedPerPay = new Menu("Payment");
    pedPerPay.addMenuItem("New", "location='index.php?part=403&permit_type=peddlers'");
    pedPerPay.addMenuItem("Search", "location='index.php?part=401'");
    pedPerPay.menuItemWidth = 100;

    // Peddlers Permit --- Approval
    pedPerAppr = new Menu("Approval");
    pedPerAppr.addMenuItem("In-Progress", "location='index.php?part=503&method_of_application=new&permit_type=peddlers'");
    //pedPerAppr.addMenuItem("Renew", "location='index.php?part=503&method_of_application=renew&permit_type=peddlers'");
    //pedPerAppr.addMenuItem("Retire", "location='index.php?part=503&method_of_application=retire&permit_type=peddlers'");
    pedPerAppr.addMenuItem("Search", "location='index.php?part=501'");
    pedPerAppr.menuItemWidth = 100;

    // Peddlers Permit --- Releasing
    pedPerRel = new Menu("Releasing");
    pedPerRel.addMenuItem("In-Progress", "location='index.php?part=603&method_of_application=new&permit_type=peddlers'");
    //pedPerRel.addMenuItem("Renew", "location='index.php?part=603&method_of_application=renew&permit_type=peddlers'");
    //pedPerRel.addMenuItem("Retire", "location='index.php?part=603&method_of_application=retire&permit_type=peddlers'");
    pedPerRel.addMenuItem("Search", "location='index.php?part=601'");
    pedPerRel.menuItemWidth = 100;

    // Peddlers Permit
    pedPer = new Menu("Peddlers Permit");
    pedPer.addMenuItem(pedPerApp);
    pedPer.addMenuItem(pedPerAss);
    pedPer.addMenuItem(pedPerPay);
    pedPer.addMenuItem(pedPerAppr);
    pedPer.addMenuItem(pedPerRel);
    pedPer.menuItemWidth = 100;
	
    	
    // Franchise Permit --- Application
    franPerApp = new Menu("Application");
    franPerApp.addMenuItem("New", "location='index.php?part=214&method_of_application=new&permit_type=franchise'");
    franPerApp.addMenuItem("Renew", "location='index.php?part=214&method_of_application=renew&permit_type=franchise'");
    franPerApp.addMenuItem("Retire", "location='index.php?part=214&method_of_application=retire&permit_type=franchise'");
    franPerApp.addMenuItem("Search", "location='index.php?part=201'");
    franPerApp.menuItemWidth = 100;
    
    // Franchise Permit --- Assessment
    franPerAss = new Menu("Assessment");
    franPerAss.addMenuItem("In-Progress", "location='index.php?part=303&method_of_application=new&permit_type=franchise'");
    //franPerAss.addMenuItem("Renew", "location='index.php?part=303&method_of_application=renew&permit_type=franchise'");
    //franPerAss.addMenuItem("Retire", "location='index.php?part=303&method_of_application=retire&permit_type=franchise'");
    franPerAss.addMenuItem("Search", "location='index.php?part=301'");
    franPerAss.menuItemWidth = 100;
    
    // Franchise Permit --- Payment
    franPerPay = new Menu("Payment");
    franPerPay.addMenuItem("New", "location='index.php?part=403&permit_type=franchise'");
    franPerPay.addMenuItem("Search", "location='index.php?part=401'");
    franPerPay.menuItemWidth = 100;
    
    // Franchise Permit --- Approval
    franPerAppr = new Menu("Approval");
    franPerAppr.addMenuItem("In-Progress", "location='index.php?part=503&method_of_application=new&permit_type=franchise'");
    //franPerAppr.addMenuItem("Renew", "location='index.php?part=503&method_of_application=renew&permit_type=franchise'");
    //franPerAppr.addMenuItem("Retire", "location='index.php?part=503&method_of_application=retire&permit_type=franchise'");
    franPerAppr.addMenuItem("Search", "location='index.php?part=501'");
    franPerAppr.menuItemWidth = 100;
    
    // Franchise Permit --- Releasing
    franPerRel = new Menu("Releasing");
    franPerRel.addMenuItem("In-Progress", "location='index.php?part=603&method_of_application=new&permit_type=franchise'");
    //franPerRel.addMenuItem("Renew", "location='index.php?part=603&method_of_application=renew&permit_type=franchise'");
    //franPerRel.addMenuItem("Retire", "location='index.php?part=603&method_of_application=retire&permit_type=franchise'");
    franPerRel.addMenuItem("Search", "location='index.php?part=601'");
    franPerRel.menuItemWidth = 100;
    
    // Franchise Permit
    franPer = new Menu("Franchise Permit");
    franPer.addMenuItem(franPerApp);
    franPer.addMenuItem(franPerAss);
    franPer.addMenuItem(franPerPay);
    franPer.addMenuItem(franPerAppr);
    franPer.addMenuItem(franPerRel);
    franPer.menuItemWidth = 100;
    
    
    // Fishery Permit --- Application
    fishPerApp = new Menu("Application");
    fishPerApp.addMenuItem("New", "location='index.php?part=215&method_of_application=new&permit_type=fishery'");
    fishPerApp.addMenuItem("Renew", "location='index.php?part=215&method_of_application=renew&permit_type=fishery'");
    fishPerApp.addMenuItem("Retire", "location='index.php?part=215&method_of_application=retire&permit_type=fishery'");
    fishPerApp.addMenuItem("Search", "location='index.php?part=201'");
    fishPerApp.menuItemWidth = 100;

    // Fishery Permit --- Assessment
    fishPerAss = new Menu("Assessment");
    fishPerAss.addMenuItem("In-Progress", "location='index.php?part=303&method_of_application=new&permit_type=fishery'");
    //fishPerAss.addMenuItem("Renew", "location='index.php?part=303&method_of_application=renew&permit_type=fishery'");
    //fishPerAss.addMenuItem("Retire", "location='index.php?part=303&method_of_application=retire&permit_type=fishery'");
    fishPerAss.addMenuItem("Search", "location='index.php?part=301'");
    fishPerAss.menuItemWidth = 100;

    // Fishery Permit --- Payment
    fishPerPay = new Menu("Payment");
    fishPerPay.addMenuItem("New", "location='index.php?part=403&permit_type=fishery'");
    fishPerPay.addMenuItem("Search", "location='index.php?part=401'");
    fishPerPay.menuItemWidth = 100;

    // Fishery Permit --- Approval
    fishPerAppr = new Menu("Approval");
    fishPerAppr.addMenuItem("In-Progress", "location='index.php?part=503&method_of_application=new&permit_type=fishery'");
    //fishPerAppr.addMenuItem("Renew", "location='index.php?part=503&method_of_application=renew&permit_type=fishery'");
    //fishPerAppr.addMenuItem("Retire", "location='index.php?part=503&method_of_application=retire&permit_type=fishery'");
    fishPerAppr.addMenuItem("Search", "location='index.php?part=501'");
    fishPerAppr.menuItemWidth = 100;

    // Fishery Permit --- Releasing
    fishPerRel = new Menu("Releasing");
    fishPerRel.addMenuItem("In-Progress", "location='index.php?part=603&method_of_application=new&permit_type=fishery'");
    //fishPerRel.addMenuItem("Renew", "location='index.php?part=603&method_of_application=renew&permit_type=fishery'");
    //fishPerRel.addMenuItem("Retire", "location='index.php?part=603&method_of_application=retire&permit_type=fishery'");
    fishPerRel.addMenuItem("Search", "location='index.php?part=601'");
    fishPerRel.menuItemWidth = 100;

    // Fishery Permit
    fishPer = new Menu("Fishery Permit");
    fishPer.addMenuItem(fishPerApp);
    fishPer.addMenuItem(fishPerAss);
    fishPer.addMenuItem(fishPerPay);
    fishPer.addMenuItem(fishPerAppr);
    fishPer.addMenuItem(fishPerRel);
    fishPer.menuItemWidth = 100;


    // Permit
    permit = new Menu("Permit");
    permit.addMenuItem(bizPer);  
    permit.addMenuItem(motorPer, "top.window.location=''");
    permit.addMenuItem(occPer, "top.window.location=''");
    permit.addMenuItem(pedPer, "top.window.location=''");
    permit.addMenuItem(franPer, "top.window.location=''");
    permit.addMenuItem(fishPer, "top.window.location=''");
    permit.menuItemWidth = 175;
 
    // Settings --- System References
    sysRef = new Menu("System References");
    sysRef.addMenuItem("Tax / Fee Manager", "location='index.php?part=280'");
    sysRef.addMenuItem("Formula Manager", "location='index.php?part=25'");
    sysRef.addMenuItem("Chart of Accounts", "location='index.php?part=800'");
    sysRef.addMenuItem("Tax / Fee / Permit Default Requirements", "location='index.php?part=900'");
    sysRef.addMenuItem("Ownership Type", "location='index.php?part=18&selMode=ebpls_business_category'");
    //sysRef.addMenuItem("Business Category Office Codes", "location='index.php?part=18&selMode=ebpls_business_category_offc'");
    sysRef.addMenuItem("Business Nature", "location='index.php?part=18&selMode=ebpls_business_nature'");
    sysRef.addMenuItem("Business Requirements", "location='index.php?part=18&selMode=ebpls_business_requirement'");
    sysRef.addMenuItem("Business Status", "location='index.php?part=18&selMode=ebpls_business_status'");
    //sysRef.addMenuItem("Business Type", "location='index.php?part=18&selMode=ebpls_business_type'");
    sysRef.addMenuItem("LGU Name Codes", "location='index.php?part=18&selMode=ebpls_city_municipality'");
    sysRef.addMenuItem("District Codes", "location='index.php?part=18&selMode=ebpls_district'");
    sysRef.addMenuItem("Province Codes", "location='index.php?part=18&selMode=ebpls_province'");
    sysRef.addMenuItem("Zip Codes", "location='index.php?part=18&selMode=ebpls_zip'");
    sysRef.addMenuItem("Zone Codes", "location='index.php?part=18&selMode=ebpls_zone'");
    sysRef.addMenuItem("Industry Sector Codes", "location='index.php?part=18&selMode=ebpls_industry_sector'");
    sysRef.addMenuItem("Occupancy Type Codes", "location='index.php?part=18&selMode=ebpls_occupancy_type'");
    sysRef.addMenuItem("Barangay Codes", "location='index.php?part=18&selMode=ebpls_barangay'");
    
    sysRef.menuItemWidth = 235;


    // Settings
    settings = new Menu("Settings");
    settings.addMenuItem("System Settings", "top.window.location='index.php?part=6'");  
    settings.addMenuItem("User Manager", "top.window.location='index.php?part=7'");
    settings.addMenuItem(sysRef);
    settings.addMenuItem("Activity Logs", "top.window.location='index.php?part=11'");
    settings.addMenuItem("Color Scheme Preferences", "location='index.php?part=23'");
    //settings.addMenuItem("Change LGU Logo", "location='uploadlogo.php'");
    settings.addMenuItem("Change LGU Info", "location='index.php?part=21'");
    settings.menuItemWidth = 175;
     
    bizBy.writeMenus();

}
