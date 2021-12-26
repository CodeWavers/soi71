<?php $this->load->view('header'); ?>

<section class="inner-banner" style="background-image: url('<?php echo ($terms_and_conditions[0]->image)?image_url.$terms_and_conditions[0]->image:cms_banner_img?>');">
    <div class="container">
        <div class="inner-pages-banner">
            <h1>Documentation</h1>
        </div>
    </div>
</section>
<section class="page-wrapper contact-us-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-3 ">
                <div class="sidebar-help-center">
                    <div class="top-section-g">
                        <!-- <div class="heading-title">
                            <h2>Quick Searches</h2>
                        </div> -->
                        <ul class="top-section-g-scroll help-sidebar-list">
                            <li class="active">
                                <h6>Get Started</h6>
                            </li>
                            <li>
                                <h6>Eatance Admin Panel setup</h6>
                                <ul class="sub-list">
                                    <li><a href="#one1">Step1: Open cPanel Application, log in with your credentials</a></li>
                                    <li><a href="#one2">Step2: Upload Source Code</a></li>
                                    <li><a href="#one3">Step3: Create your Database</a></li>
                                    <li><a href="#one4">Step4: Adding a New Database User</a></li>
                                    <li><a href="#one5">Step5: Assign all privileges to the user</a></li>
                                    <li><a href="#one6">Step6: Import Database</a></li>
                                    <li><a href="#one7">Step7: Setup Database to Source Code</a></li>
                                    <li><a href="#one8">Step8: Update the important details in the database</a></li>
                                    <li><a href="#one9">For Back end Access</a></li>
                                </ul>

                            </li>
                            <!-- <li>
                                <h6>List of Figures</h6>
                                <ul class="sub-list">
                                    <li><a href="#two1">Figure1:cPanel Setup Application</a></li>
                                    <li><a href="#two2">Figure2:Select FileManager Option</a></li>
                                    <li><a href="#two3">Figure3:Select public_html option</a></li>
                                    <li><a href="#two4">Figure4:Select the file</a></li>
                                    <li><a href="#two5">Figure5:Select.zip File</a></li>
                                    <li><a href="#two6">Figure6:Select Extract Option</a></li>
                                    <li><a href="#two7">Figure7:Extract File</a></li>
                                    <li><a href="#two8">Figure8:Select the MYSQL Databases option</a></li>
                                    <li><a href="#two9">Figure9:Creating your Database</a></li>
                                    <li><a href="#two10">Figure10:Database Added</a></li>
                                    <li><a href="#two11">Figure11:Adding a New Database User</a></li>
                                    <li><a href="#two12">Figure12:Generate Password</a></li>
                                    <li><a href="#two13">Figure13:Creating a new user</a></li>
                                    <li><a href="#two14">Figure14:Successful creation of New User</a></li>
                                    <li><a href="#two15">Figure15:Add user to a database</a></li>
                                    <li><a href="#two16">Figure16:Manage User Privileges Option</a></li>
                                    <li><a href="#two17">Figure17:Import Data base Option</a></li>
                                    <li><a href="#two18">Figure18:Importing Your database</a></li>
                                    <li><a href="#two19">Figure19:Click on the Importbutton</a></li>
                                    <li><a href="#two20">Figure20:Click on the GO button</a></li>
                                    <li><a href="#two21">Figure21:Database.php file</a></li>
                                    <li><a href="#two22">Figure22:Select Edit Option</a></li>
                                    <li><a href="#two23">Figure23:Select Edit button</a></li>
                                    <li><a href="#two24">Figure24:Update Database Details</a></li>
                                    <li><a href="#two25">Figure25:Login to Eatance application</a></li>
                                    <li><a href="#two26">Figure26:Admin Dashboard of Eatance</a></li>
                                    <li><a href="#two27">Figure27:Edit option for.htaccessfile</a></li>
                                    <li><a href="#two28">Figure28:Select Edit button</a></li>
                                    <li><a href="#two29">Figure29:Backend Access Folder</a></li>
                                </ul>
                            </li> -->
                            <li>
                                <h6>Eatance Mobile APP Setup for Android</h6>
                                <ul class="sub-list">
                                    <li><a href="#mob1">Setting up React Native</a></li>
                                    <li><a href="#mob2">Android Set Up</a></li>
                                    <li><a href="#mob3">Running on android</a></li>
                                    <li><a href="#mob4">Obtaining a google maps API key</a></li>
                                    <li><a href="#mob5">Setting up the firebase for android</a></li>
                                    <li><a href="#mob6">Creating a signed app for play store release</a></li>
                                </ul>
                            </li>
                            <li>
                                <h6>Eatance Mobile APP Setup for iOS</h6>
                                <ul class="sub-list">
                                    <li><a href="#mobios1">Setting up React Native</a></li>
                                    <li><a href="#mobios2">iOS Setup</a></li>
                                    <li><a href="#mobios3">Create a Release Build.</a></li>
                                    <li><a href="#mobios4">Generate Map API key.</a></li>
                                    <li><a href="#mobios5">Firebase Setup</a></li>
                                </ul>
                            </li>
                            <li>
                                <h6>ReadME - Eatance</h6>
                                <ul class="sub-list">
                                    <li><a href="#readme1">Support Pre-Requisites:</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="#faq">Eatance FAQ</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-9">
                <div class="help-center-body" id="top">
                    <!-- <div class="heading-title-02 heading-title">
                        <h4>Help Center <span>&amp; Resources</span></h4>
                    </div> -->
                    <div class="g-section side-body-section" id="one1">
                        <h3 class="g-section-heading">Get Started</h3>
                        <div class="g-section-content">
                            <p>Thank you for purchasing our app</p>
                            <p>Please read the documentation carefully , and if you have any question check us .It's recommended to check the written Documentation .</p>
                        </div>
                    </div>
                    <div class="g-section side-body-section" id="one1">
                        <h3 class="g-section-heading">Step 1: Open cPanel Application, log in with your credentials.</h3>
                        <div class="g-section-content">
                            <div class="doc-img">
                                <img src="<?php echo base_url();?>/assets/front/images/step1.png">
                                <figcaption>Figure 1: cPanel Setup Application</figcaption>
                            </div>
                            <ul>
                                <li>Enter your <strong>username</strong> and <strong>password</strong>,to login to cPanel application.</li>
                            </ul>
                        </div>
                    </div>
                    <div class="g-section side-body-section" id="one2">
                        <h3 class="g-section-heading">Step2: Upload Source Code.</h3>
                        <div class="g-section-content">
                            <ul>
                                <li>
                                    Click on the <strong>Files</strong> section, to put your package on domain, select the <strong>File Manager</strong> option.
                                    <div class="doc-img">
                                        <img src="<?php echo base_url();?>/assets/front/images/step2Fig1.png">
                                        <figcaption>Figure 2: Select File Manager Option</figcaption>
                                    </div>
                                </li>
                                <li>
                                    Click on the <strong>public_html</strong> folder, as shown in the figure below:
                                    <div class="doc-img">
                                        <img src="<?php echo base_url();?>/assets/front/images/step2Fig2.png">
                                        <figcaption>Figure 3:Select public_html option</figcaption>
                                    </div>
                                </li>
                                <li>
                                    Click on the <strong>Upload</strong> option from the top panel, the following figure will appear:
                                    <div class="doc-img">
                                        <img src="<?php echo base_url();?>/assets/front/images/step2Fig3.png">
                                        <figcaption>Figure 4: Select the file</figcaption>
                                    </div>
                                </li>
                                <li>To upload a file, click on the <strong>Select File</strong> button and upload <strong>Zip file</strong>.</li>
                                <li>
                                    After uploading file, click on the <strong>.zip file</strong> from the list, as shown in the figure below:
                                    <div class="doc-img">
                                        <img src="<?php echo base_url();?>/assets/front/images/step2Fig4.png">
                                        <figcaption>Figure 5: Select .zip File</figcaption>
                                    </div>
                                </li>
                                <li>
                                    Right-click on the <strong>.zip</strong> file, select <strong>extract</strong> option.
                                    <div class="doc-img">
                                        <img src="<?php echo base_url();?>/assets/front/images/step2Fig5.png">
                                        <figcaption>Figure 6: Select Extract Option</figcaption>
                                    </div>
                                </li>
                                <li>
                                    Click on the <strong>Extract</strong> Option, to extract <strong>.zip</strong> file. Following pop-up will appear:
                                    <div class="doc-img">
                                        <img src="<?php echo base_url();?>/assets/front/images/step2Fig6.png">
                                        <figcaption>Figure 7: Extract File</figcaption>
                                    </div>
                                </li>
                                <li>
                                    Now, if you have saved your file in public_html folder. Click on the Extract File(s) button as shown in the above figure.
                                </li>
                                <li>Or else if your file is saved inside the folder, then you need to write the <strong>/public_html/</strong>Name of the folder (For Example, <strong>Eatance</strong>) and then click on the <strong>Extract File(s)</strong> button and <strong>upload code.</strong></li>
                            </ul>
                        </div>
                    </div>
                    <div class="g-section side-body-section" id="one3">
                        <h3 class="g-section-heading">Step3: Create your Database.</h3>
                        <div class="g-section-content">
                            <ul>
                                <li>
                                    Go to <strong>Databases</strong> section, select <strong>MySQL Databases</strong> option.
                                    <div class="doc-img">
                                        <img src="<?php echo base_url();?>/assets/front/images/step3Fig1.png">
                                        <figcaption>Figure 8: Select the MYSQL Databases option.</figcaption>
                                    </div>
                                </li>
                                <li>
                                    After clicking on the <strong>MYSQL Databases</strong> option, create your new database.
                                    <div class="doc-img">
                                        <img src="<?php echo base_url();?>/assets/front/images/step3Fig2.png">
                                        <figcaption>Figure 9: Creating your Database.</figcaption>
                                    </div>
                                </li>
                                <li>
                                    Enter the name of your <strong>New Database</strong> as shown in the above figure, click on the <strong>Create Database button</strong>, the following figure will appear:
                                    <div class="doc-img">
                                        <img src="<?php echo base_url();?>/assets/front/images/step3Fig3.png">
                                        <figcaption>Figure 10: Database Added</figcaption>
                                    </div>
                                </li>
                                <li>
                                    Click on the <strong>Go Back</strong> button to go back to Add Database section.
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="g-section side-body-section" id="one4">
                        <h3 class="g-section-heading">Step4: Adding a New Database User.</h3>
                        <div class="g-section-content">
                            <ul>
                                <li>
                                    To create a new user, go to the <strong>Add New</strong> User section. Enter Username, Password and Re-enter Password in Password(Again) textbox as shown in the below figure.
                                    <div class="doc-img">
                                        <img src="<?php echo base_url();?>/assets/front/images/step4Fig1.png">
                                        <figcaption>Figure 11: Adding a New Database User.</figcaption>
                                    </div>
                                </li>
                                <li>
                                    As shown in the above figure, click on the Create User button.
                                </li>
                                <li>
                                    Now, suppose if you <strong>do not wish</strong> to enter your own password manually, then click on the <strong>Password Generator</strong> button, which will generate system password for you, the following figure will appear:
                                    <div class="doc-img">
                                        <img src="<?php echo base_url();?>/assets/front/images/step4Fig2.png">
                                        <figcaption>Figure 12: Generate Password</figcaption>
                                    </div>
                                </li>
                                <li>
                                    As shown in the above figure, click on the <strong>Generate Password</strong> button, which will create a password in the textbox (like <strong>6PjAOycV0@P</strong>). <strong>Tick mark</strong> the  option, then click on the <strong>Use Password</strong> button.
                                    <div class="doc-img">
                                        <img src="<?php echo base_url();?>/assets/front/images/step4Fig3.png">
                                        <figcaption>Figure 13: Creating a new user.</figcaption>
                                    </div>
                                </li>
                                <li>
                                    Click on the <strong>Create User</strong> button, following figure will appear.
                                    <div class="doc-img">
                                        <img src="<?php echo base_url();?>/assets/front/images/step4Fig4.png">
                                        <figcaption>Figure 14: Successful creation of New User</figcaption>
                                    </div>
                                </li>
                                <li>
                                    Now, click on the <strong>Go Back</strong> button. You will be reverted to <strong>Add New Database</strong> section, scroll down to <strong>Add User to Database</strong> sections.
                                    <div class="doc-img">
                                        <img src="<?php echo base_url();?>/assets/front/images/step4Fig5.png">
                                        <figcaption>Figure 15: Add user to a database</figcaption>
                                    </div>
                                </li>
                                <li>
                                    As shown in the above figure, select a User name from the list and its corresponding new created database from the list. Click on the <strong>Add</strong> button, to go to <strong>Manage User Privileges</strong> page.
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="g-section side-body-section" id="one5">
                        <h3 class="g-section-heading">Step 5: Assign all privileges to the user.</h3>
                        <div class="g-section-content">
                            <ul>
                                <li>
                                    To create a new user, go to the <strong>Add New</strong> User section. Enter Username, Password and Re-enter Password in Password(Again) textbox as shown in the below figure.
                                    <div class="doc-img">
                                        <img src="<?php echo base_url();?>/assets/front/images/step5Fig1.png">
                                        <figcaption>Figure 16:Manage User Privileges Option</figcaption>
                                    </div>
                                </li>
                                <li>
                                    As shown in the above figure, <strong>Tick mark</strong> on <img src="<?php echo base_url();?>/assets/front/images/all-privi.png"> to assign all the privileges (rights) to the created users from <strong>Manage User</strong> Privileges section. Click on <strong>Go Back</strong> option, to go to the previous page.
                                </li>
                                <li>
                                    Click on the <img src="<?php echo base_url();?>/assets/front/images/grid.png"> button, to go to <strong>cPanel</strong> Dashboard.
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="g-section side-body-section" id="one6">
                        <h3 class="g-section-heading">Step 6: Import Database.</h3>
                        <div class="g-section-content">
                            <ul>
                                <li>
                                    To import the created database (For Database Connectivity), select phpMyAdmin option as shown in the figure below:
                                    <div class="doc-img">
                                        <img src="<?php echo base_url();?>/assets/front/images/step6Fig1.png">
                                        <figcaption>Figure 17: Import Database Option</figcaption>
                                    </div>
                                </li>
                                <li>
                                    Select the name of your database, as shown in the below figure.
                                    <div class="doc-img">
                                        <img src="<?php echo base_url();?>/assets/front/images/step6Fig2.png">
                                        <figcaption>Figure 18: Importing Your database</figcaption>
                                    </div>
                                </li>
                                <li>
                                    Click on the Import button as shown in the below figure:
                                    <div class="doc-img">
                                        <img src="<?php echo base_url();?>/assets/front/images/step6Fig3.png">
                                        <figcaption>Figure 19: Click on the Import button</figcaption>
                                    </div>
                                </li>
                                <li>
                                    Select .sql file, your package from your system. Scroll Down and click on the GO Button, as shown in the below figure:
                                    <div class="doc-img">
                                        <img src="<?php echo base_url();?>/assets/front/images/step6Fig4.png">
                                        <figcaption>Figure 20: Click on the GO button</figcaption>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="g-section side-body-section" id="one7">
                        <h3 class="g-section-heading">Step 7: Setup Database to Source Code.</h3>
                        <div class="g-section-content">
                            <ul>
                                <li>
                                    To update your database access in the file, open the source code folder. For that you need to click on the <</strong>strong>File Manager</strong> option. Further, Click on the <strong>public_html</strong> option.
                                </li>
                                <li>
                                    If you have created a folder then click on the folder name (For Eg. Eatance), then click on the <strong>application ->Config-> database.php</strong> option.
                                </li>
                                <li>
                                    Or else directly access source code from the location path of application ->Config-> database.php option.
                                    <div class="doc-img">
                                        <img src="<?php echo base_url();?>/assets/front/images/step7Fig1.png">
                                        <figcaption>Figure 21: Database.php file</figcaption>
                                    </div>
                                </li>
                                <li>
                                    Here, right click on the <strong>database.php</strong> file as shown in the figure below:
                                    <div class="doc-img">
                                        <img src="<?php echo base_url();?>/assets/front/images/step7Fig2.png">
                                        <figcaption>Figure 22: Select Edit Option</figcaption>
                                    </div>
                                </li>
                                <li>
                                    Click on the <strong>edit</strong> button, to update the database details following pop-up will appear:
                                    <div class="doc-img">
                                        <img src="<?php echo base_url();?>/assets/front/images/step7Fig3.png">
                                        <figcaption>Figure 23: Select Edit button</figcaption>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="g-section side-body-section" id="one8">
                        <h3 class="g-section-heading">Step 8: Update the important details in the database.</h3>
                        <div class="g-section-content">
                            <ul>
                                <li>
                                    Scroll down, to update <strong>database</strong> details.
                                    <div class="doc-img">
                                        <img src="<?php echo base_url();?>/assets/front/images/step8Fig1.png">
                                        <figcaption>Figure 24: Update Database Details</figcaption>
                                    </div>s
                                </li>
                                <li>
                                    As highlighted in the above figure, update the following information in the database.
                                    <ol>
                                        <li><strong>Host Name:</strong> localhost</li>
                                        <li><strong>Username:</strong> ‘database user’ (For Example: eatance1).</li>
                                        <li><strong>Password:</strong> ‘database user’s password’ (For Example: abcUe@H=clIs).</li>
                                        <li><strong>Database:</strong> ‘database Name’ (For Example: eatance1).</li>
                                    </ol>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="g-section side-body-section" id="one9">
                        <h3 class="g-section-heading">For Back end Access:</h3>
                        <div class="g-section-content">
                            <ul>
                                <li>
                                   To get the back end access, Enter your folder name in your URL,                                     
                                </li>
                                <li><strong>For Example: <span class="doclink">http://example.com/”backoffice/”</span></strong></li>
                                <li>
                                    Here,” <strong>backoffice”</strong> is folder name for back end access. The following figure will appear:
                                    <div class="doc-img">
                                        <img src="<?php echo base_url();?>/assets/front/images/step8Fig2.png">
                                        <figcaption>Figure 25: Login to Eatance application</figcaption>
                                    </div>
                                </li>
                                <li>
                                    Enter your Email and Password in the textbox and click on Login button.
                                </li>
                                <li>
                                    After Login, following <strong>Admin Dashboard</strong> of Eatance application will appear:
                                    <div class="doc-img">
                                        <img src="<?php echo base_url();?>/assets/front/images/step8Fig3.png">
                                        <figcaption>Figure 26: Admin Dashboard of Eatance</figcaption>
                                    </div>
                                </li>
                                <h5>Note1:</h5>
                                <li>Now, if you have added your package in the <strong>public_html</strong> folder, then it will work.</li>
                                <li>
                                    But suppose, if you have created another folder inside <strong>public_html</strong> and uploaded package into that specific folder, then you need to change it from <strong>.htaccess</strong> file, it will be on root path as shown in the figure below:
                                    <div class="doc-img">
                                        <img src="<?php echo base_url();?>/assets/front/images/step8Fig4.png">
                                        <figcaption>Figure 27: Edit option for .htaccess file</figcaption>
                                    </div>
                                </li>
                                <li>
                                    Select <strong>Edit</strong> option, from the above list. Following pop-up will appear:
                                    <div class="doc-img">
                                        <img src="<?php echo base_url();?>/assets/front/images/step8Fig5.png">
                                        <figcaption>Figure 28: Select Edit button</figcaption>
                                    </div>
                                </li>
                                <li>
                                    Now, here you are required to enter the specific folder name, in <strong>Rewrite base / Your folder name /</strong>, as shown in below figure:
                                    <div class="doc-img">
                                        <img src="<?php echo base_url();?>/assets/front/images/step8Fig6.png">
                                        <figcaption>Figure 29: Backend Access Folder</figcaption>
                                    </div>
                                </li>
                                <li>
                                    Set your folder name and if you have inserted your package in the folder then for backend access, you need to access it with the following URL (by entering the name of your folder). <span class="doclink">http://example.com/your_folder_name/backoffice</span>
                                </li>
                                <h5>Note2:</h5>
                                <li>If Domain or Subdomain points to a specific directory, then no need to change in <strong>.htaccess</strong> file.</li>
                                <li>If Domain or subdomain does not point to a specific directory, then specify folder name in <strong>.htaccess</strong> file.</li>
                            </ul>
                        </div>
                    </div>

                    <div class="g-section side-body-section" id="mob1">
                        <h3 class="g-section-heading">Setting up React Native</h3>
                        <div class="g-section-content">
                            <ul>
                                <li>
                                   As our project is based on react native, we need to set up react native to run the project.
                                </li>
                                <li>
                                    To ensure easy run of the project , you are advised to install the following softwares in your pc.
                                    <ol>
                                        <li>Visual studio code</li>
                                        <li>Node JS 12.x.x or higher</li>
                                    </ol>
                                </li>
                                <li>
                                    Download visual studio code from the following URL 
                                    <p><a class="doclink" target="_blank" href="https://code.visualstudio.com/download">https://code.visualstudio.com/download</a></p>
                                    <div class="doc-img">
                                        <img src="<?php echo base_url();?>/assets/front/images/mobileapp1.png">
                                    </div>
                                </li>
                                <li>
                                    Download the Node JS from the following URL
                                    <p><a class="doclink" target="_blank" href="https://nodejs.org/en/download/">https://nodejs.org/en/download/</a></p>
                                    <div class="doc-img">
                                        <img src="<?php echo base_url();?>/assets/front/images/mobileapp2.png">
                                    </div>
                                </li>
                                <li>
                                    After installing the above softwares,  we are now ready to open the preoject in the visual studio code.
                                    <p><a class="doclink" target="_blank" href="https://nodejs.org/en/download/">https://nodejs.org/en/download/</a></p>
                                    <div class="doc-img">
                                        <img src="<?php echo base_url();?>/assets/front/images/mobileapp3.png">
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="g-section side-body-section" id="mob2">
                        <h3 class="g-section-heading">Android Set Up</h3>
                        <div class="g-section-content">
                            <ul>
                                <li>
                                   In this section, we will discuss about installation of android studio and java. We need this software to install our apps on android devices.
                                </li>
                                <li>
                                    <p>First install java from this URL</p>
                                    <a href="https://www.java.com/en/download/" target="_blank" class="doclink">https://www.java.com/en/download/</a>
                                </li>
                                <li>
                                    <p>Then we have to install android studio , you can install the same from their official site from the following URL.</p>
                                    <a href="https://developer.android.com/studio/" class="doclink" target="_blank">https://developer.android.com/studio/</a>
                                    <div class="doc-img">
                                        <img src="<?php echo base_url();?>/assets/front/images/mobileapp1.png">
                                    </div>
                                </li>
                                <li>
                                    <p>To finalize the environment set up , please refer to the following URL.</p>
                                    <a href="https://developer.android.com/studio/install" class="doclink" target="_blank">https://developer.android.com/studio/install</a>
                                </li>
                                <li>
                                    Now we are ready with all required software.
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="g-section side-body-section" id="mob3">
                        <h3 class="g-section-heading">Running on android</h3>
                        <div class="g-section-content">
                            <ul>
                                <li>
                                   Open the project in the visual studio code with the following project structure.
                                   <div class="doc-img">
                                       <img src="<?php echo base_url();?>/assets/front/images/mobileapp4.png">
                                   </div>
                                </li>
                                <li>
                                    <p>Install the third party dependencies by running the following command in the below terminal.</p>
                                    <ol>
                                        <li><strong>npm install</strong></li>
                                    </ol>
                                </li>
                                <li>
                                    To link all the newly installed module, now we need to run the following command.
                                    <ol>
                                        <li><strong>react-native link</strong></li>
                                    </ol>
                                </li>
                                <li>
                                    Now after all the modules are linked we are ready to run the app by running the following command. Please ensure an actual device connected with usb debugging turned on or have an emulator running.
                                    <ol>
                                        <li>react-native run-android</li>
                                    </ol>
                                </li>
                                <p><strong>P.S:</strong>  If running the app fails for you, please try again running react-native run-android command again.</p>
                                <li>
                                    Still you face any error while running ,please run the following commands.
                                    <ol>
                                        <li><strong>cd android</strong></li>
                                        <li><strong>./gradlew clean</strong></li>
                                        <li><strong>cd .</strong></li>
                                        <li><strong>react-native run-android.</strong></li>
                                    </ol>
                                </li>
                                <li>Otherwise delete the following folder in file manager</li>
                                <li>
                                    Navigate to Eatance -> android-> app and delete build folder.
                                    <div class="doc-img">
                                        <img src="<?php echo base_url();?>/assets/front/images/mobileapp5.png">
                                    </div>
                                </li>
                                <li>
                                    And run the following command again.
                                    <ol>
                                        <li>react-native run-android</li>
                                    </ol>
                                </li>
                                <li>
                                    Once we successfully run on android device, you will like to change the application ID and package name according to your requirement.
                                </li>
                                <li>
                                    To perform this, you need to finalize a package name, let in our case it be <strong>com.myfoodapp</strong>.
                                </li>
                                <li>
                                    <p>Now go to the following directory in file explorer.</p>
                                    <strong>Eatance\android\app\src\main\java</strong>
                                </li>
                                <li>
                                    Create a new folder named <strong>com</strong> inside <strong>java</strong> folder, then move inside the <strong>com</strong> folder, create another folder inside the <strong>com</strong> folder , named <strong>myfoodapp</strong>.
                                </li>
                                <li>
                                    Now our folder structure is ready, let’s move the files inside the <strong>Eatance\android\app\src\main\java\com\eatance</strong>  to the <strong>myfoodapp</strong> folder.
                                </li>
                                <li>
                                    Now start android studio and open the following directory as shown in the picture below.
                                    <div class="doc-img">
                                        <img src="<?php echo base_url();?>/assets/front/images/mobileapp6.png">
                                    </div>
                                </li>
                                <li>
                                    You will see a screen like this.
                                    <div class="doc-img">
                                        <img src="<?php echo base_url();?>/assets/front/images/mobileapp7.png">
                                    </div>
                                </li>
                                <li>
                                    Change com.eatance to your desired package name, here in our example to com.myfoodapp in the following files.
                                    <div class="doc-img">
                                        <img src="<?php echo base_url();?>/assets/front/images/mobileapp8.png">
                                    </div>
                                </li>
                                <li>
                                    In the AndroidManifest.xml file change the package name to com.myfoodapp .
                                </li>
                                <li>
                                    You can change the app name by editing the following file. Go to the <strong>android\app\src\main\res\values\strings.xml.</strong> And change Eatance to your desired app name , let it be MyFoodApp here.
                                    <div class="doc-img">
                                        <img src="<?php echo base_url();?>/assets/front/images/mobileapp9.png">                                        
                                    </div>
                                </li>
                                <li>
                                    You can also change app theme color by chnaging the colorAccent to desired hex color code in the <strong>styles.xml</strong> file.
                                    <div class="doc-img">
                                        <img src="<?php echo base_url();?>/assets/front/images/mobileapp10.png">
                                    </div>
                                </li>
                                <li>
                                    You can change the app logo by replacing the images inside this folder with your required images ( Remember to rename the new images as the old image names ) .
                                    <ol>
                                        <li>Eatance\android\app\src\main\res\drawable</li>
                                    </ol>
                                </li>
                                <li>
                                    You have to change the applicationId in the following file
                                    <p><strong>Eatance\android\app\build.gradle</strong>  from <strong>com.eatance</strong> to your required package name , in our case it’s com.myfoodapp . Also change versionCode to 1 and versionName to 1.0 .</p>
                                    <div class="doc-img">
                                        <img src="<?php echo base_url();?>/assets/front/images/mobileapp11.png">
                                    </div>
                                </li>
                                <li>
                                    You have to specify google maps API key in 2 places in the app to load the map. Open <strong>Eatance\app\utils\Constants.js</strong> in visual studio code. Please refer next guide, how to get a google maps API key.
                                    <div class="doc-img">
                                        <img src="<?php echo base_url();?>/assets/front/images/mobileapp12.png">
                                    </div>
                                </li>
                                <li>
                                    And also here inside the AndroidManifest.xml file.
                                    <div class="doc-img">
                                        <img src="<?php echo base_url();?>/assets/front/images/mobileapp12.png">
                                    </div>
                                </li>
                                <li>
                                    Now to use app on your own server , change the BASE_URL to your backend url inside the Constants.js file.
                                    <div class="doc-img">
                                        <img src="<?php echo base_url();?>/assets/front/images/mobileapp13.png">
                                    </div>
                                </li>
                                <li>
                                    We have completed all the required steps to run our very own app except the firebase set up. Let’s do that so that we can finally run our app.
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="g-section side-body-section" id="mob4">
                        <h3 class="g-section-heading">Obtaining a google maps API key</h3>
                        <div class="g-section-content">
                            <ul>
                                <li>
                                   <p>Go to google cloud console by clicking the following link. Select your project and click on open. Let’s open My Food App.</p>
                                   <a href="https://console.cloud.google.com" class="doclink" target="_blank">https://console.cloud.google.com</a>
                                    <div class="doc-img">
                                       <img src="<?php echo base_url();?>/assets/front/images/mobileapp14.png">
                                    </div>
                                </li>
                                <li>
                                    <p>Click on side menu and select API and Services and click on Dashboard.</p>
                                    <div class="doc-img">
                                       <img src="<?php echo base_url();?>/assets/front/images/mobileapp15.png">
                                    </div>
                                </li>
                                <li>
                                    Click on ENABLE API AND SERVICES . Search for Maps SDk for Android, Geocoding API and enable them.
                                    <div class="doc-img">
                                       <img src="<?php echo base_url();?>/assets/front/images/mobileapp16.png">
                                    </div>
                                </li>
                                <li>
                                    Click on navigation menu, select API & Services and click on <strong>credentials</strong> and On the Credentials page, click Create <strong>credentials > API key</strong>.
                                    <div class="doc-img">
                                       <img src="<?php echo base_url();?>/assets/front/images/mobileapp17.png">
                                    </div>
                                </li>
                                <li>
                                    To make the APIs work you need to link a billing account or create a billing account. 
                                    <div class="doc-img">
                                       <img src="<?php echo base_url();?>/assets/front/images/mobileapp18.png">
                                    </div>
                                </li>
                                <li>
                                    Start creating a billing account by clicking CREATE BILLING ACCOUNT.
                                    <div class="doc-img">
                                       <img src="<?php echo base_url();?>/assets/front/images/mobileapp19.png">
                                    </div>
                                </li>
                                <li>
                                    Accept terms of service and click on continue. 
                                </li>
                                <li>
                                    Then add personal and payment details and click on START MY FREE TRIAL .
                                </li>
                                <li>
                                    After this we are ready to use the MAP API key in the app.
                                    <div class="doc-img">
                                       <img src="<?php echo base_url();?>/assets/front/images/mobileapp20.png">
                                    </div>
                                    <div class="doc-img">
                                       <img src="<?php echo base_url();?>/assets/front/images/mobileapp21.png">
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="g-section side-body-section" id="mob5">
                        <h3 class="g-section-heading">Setting up the firebase for android</h3>
                        <div class="g-section-content">
                            <ul>
                                <li>
                                   <p>Go to firebase console by clicking on the following url</p>
                                   <a href="https://console.firebase.google.com/" class="doclink" target="_blank">https://console.firebase.google.com/</a>
                                </li>
                                <li>
                                    Sign in to your google account, after successful login , you will land into the firebase console home page.
                                    <div class="doc-img">
                                       <img src="<?php echo base_url();?>/assets/front/images/mobileapp22.png">
                                    </div>
                                </li>
                                <li>
                                    Click on Create a project, provide a project name, accept the firebase terms and click on continue.
                                    <div class="doc-img">
                                       <img src="<?php echo base_url();?>/assets/front/images/mobileapp23.png">
                                    </div>
                                </li>
                                <li>
                                    Click on Continue
                                    <div class="doc-img">
                                       <img src="<?php echo base_url();?>/assets/front/images/mobileapp24.png">
                                    </div>
                                </li>
                                <li>
                                    Choose the analytics location, accept the terms and conditions and click on create project.
                                    <div class="doc-img">
                                       <img src="<?php echo base_url();?>/assets/front/images/mobileapp25.png">
                                    </div>
                                </li>
                                <li>
                                    Click on continue
                                    <div class="doc-img">
                                       <img src="<?php echo base_url();?>/assets/front/images/mobileapp26.png">
                                    </div>
                                </li>
                                <li>
                                    Click on the highlighted icon to add your android app.
                                    <div class="doc-img">
                                       <img src="<?php echo base_url();?>/assets/front/images/mobileapp27.png">
                                    </div>
                                </li>
                                <li>
                                    Provide the package name and register app and download the google-services,json file.
                                    <div class="doc-img">
                                       <img src="<?php echo base_url();?>/assets/front/images/mobileapp28.png">
                                    </div>
                                </li>
                                <li>
                                    Click next -> next and then click Skip this step .
                                    <div class="doc-img">
                                       <img src="<?php echo base_url();?>/assets/front/images/mobileapp29.png">
                                    </div>
                                </li>
                                <li>
                                    Click on Authentication from side bar , after that click on Sign-in method.
                                </li>
                                <li>
                                    Enable phone authentication from the menu and click on save.
                                    <div class="doc-img">
                                       <img src="<?php echo base_url();?>/assets/front/images/mobileapp30.png">
                                    </div>
                                </li>
                                <li>
                                    Click on Project settings.
                                    <div class="doc-img">
                                       <img src="<?php echo base_url();?>/assets/front/images/mobileapp31.png">
                                    </div>
                                </li>
                                <li>
                                    Click on Cloud Messaging and note the server key , which you need to set in backend for notification purposes.
                                    <div class="doc-img">
                                       <img src="<?php echo base_url();?>/assets/front/images/mobileapp32.png">
                                    </div>
                                </li>
                                <li>
                                    Now let’s set the google-services.json file we have just downloaded a while ago.
                                </li>
                                <li>
                                    Go to <strong>Eatance\android\app</strong> and replace the old google-services.json file with the newly downloaded one.
                                </li>
                                <li>
                                    For the <strong>OTP</strong> feature to work without any issue , we have to follow certain extra steps,
                                    <ol>
                                        <li>Go to Android Studio and click on gradle  on top right corner.</li>
                                        <li>
                                            From there, click on signingReport as shown in the image.
                                            <div class="doc-img">
                                               <img src="<?php echo base_url();?>/assets/front/images/mobileapp33.png">
                                            </div>
                                        </li>
                                        <li>
                                            You will see the following in build tab in the bottom of window .
                                            <div class="doc-img">
                                               <img src="<?php echo base_url();?>/assets/front/images/mobileapp34.png">
                                            </div>
                                        </li>
                                        <li>
                                            Copy the SHA1 key.
                                        </li>
                                        <li>
                                            Add the same key in the firebase console according to the following image by clicking on the Add fingerprint button.
                                            <div class="doc-img">
                                               <img src="<?php echo base_url();?>/assets/front/images/mobileapp35.png">
                                            </div>
                                        </li>
                                        <li>
                                            Now to again run  the command, <strong>react-native run-android</strong> to run the app.
                                        </li>
                                    </ol>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="g-section side-body-section" id="mob6">
                        <h3 class="g-section-heading">Creating a signed app for play store release</h3>
                        <div class="g-section-content">
                            <ul>
                                <li>
                                   To upload an app to the play store we need to create a signed apk for the project. In this section we will describe the step by step process to create a signed apk.
                                </li>
                                <li>
                                    First click on <strong>Build</strong> on the menu bar in Android Studio, then click on generates signed bundle/ APK.
                                </li>
                                <li>
                                    Select APK and click on Next.
                                    <div class="doc-img">
                                       <img src="<?php echo base_url();?>/assets/front/images/mobileapp36.png">
                                    </div>
                                </li>
                                <li>
                                    Click on Create new
                                    <div class="doc-img">
                                       <img src="<?php echo base_url();?>/assets/front/images/mobileapp37.png">
                                    </div>
                                </li>
                                <li>
                                    Choose the required path and name for JKS file, here it is Myfoodapp.jks.
                                </li>
                                <li>
                                    Fill out required details and click on Ok.
                                    <div class="doc-img">
                                       <img src="<?php echo base_url();?>/assets/front/images/mobileapp38.png">
                                    </div>
                                </li>
                                <li>
                                    Select the keystore, add alias , password and click Next.
                                    <div class="doc-img">
                                       <img src="<?php echo base_url();?>/assets/front/images/mobileapp39.png">
                                    </div>
                                </li>
                                <li>
                                    Select the following options and click on Finish.
                                    <div class="doc-img">
                                       <img src="<?php echo base_url();?>/assets/front/images/mobileapp40.png">
                                    </div>
                                </li>
                                <li>
                                    On successful run, apk will be generated in the following folder Eatance\android\app\release with name apk-release.apk .
                                </li>
                                <li>
                                    One last step is needed to make OTP work on release apk.
                                    <ol>
                                        <li>Open command prompt or powershell.</li>
                                        <li>Change directory to the location where Myfoodapp.jks file is present</li>
                                        <li>
                                            Run the following command <br>
                                            <strong>keytool -list -v -keystore {keystore_name} -alias {alias_name}</strong><br>
                                            In our case it will be<br>
                                            <strong>keytool -list -v -keystore Myfoodapp.jks -alias myfoodapp</strong>
                                        </li>
                                        <li>Now you have to enter keystore password, <strong>don’t worry if you are unable to see the password</strong>, just type it correctly and hit enter.</li>
                                        <li>
                                            You will see the release SHA1 fingerprints in the screen. 
                                            <div class="doc-img">
                                               <img src="<?php echo base_url();?>/assets/front/images/mobileapp41.png">
                                            </div>
                                        </li>
                                        <li>
                                            Copy the same and paste it in the firebase console as we did before , while adding fingerprints.
                                        </li>
                                    </ol>
                                </li>
                                <li>
                                    Now we are all set to use the app with all functionalities running fine.
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="g-section side-body-section" id="mobios1">
                        <h3 class="g-section-heading">Setting up React Native</h3>
                        <div class="g-section-content">
                            <ul>
                                <li>
                                   As our project is based on react native, we need to set up react native to run the project.
                                </li>
                                <li>
                                    To ensure easy run of the project , you are advised to install the following softwares in your pc.
                                    <ol>
                                        <li>Visual studio code</li>
                                        <li>Node JS 12.x.x or higher</li>
                                    </ol>
                                </li>
                                <li>
                                    Download visual studio code from the following URL 
                                    <p><a class="doclink" target="_blank" href="https://code.visualstudio.com/download">https://code.visualstudio.com/download</a></p>
                                    <div class="doc-img">
                                        <img src="<?php echo base_url();?>/assets/front/images/mobileapp1.png">
                                    </div>
                                </li>
                                <li>
                                    Download the Node JS from the following URL
                                    <p><a class="doclink" target="_blank" href="https://nodejs.org/en/download/">https://nodejs.org/en/download/</a></p>
                                    <div class="doc-img">
                                        <img src="<?php echo base_url();?>/assets/front/images/mobileapp2.png">
                                    </div>
                                </li>
                                <li>
                                    After installing the above software,  we are now ready to open the project in the visual studio code.
                                    <p><a class="doclink" target="_blank" href="https://nodejs.org/en/download/">https://nodejs.org/en/download/</a></p>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="g-section side-body-section" id="mobios2">
                        <h3 class="g-section-heading">iOS Setup</h3>
                        <div class="g-section-content">
                            <ul>
                                <li>Create a Bundle Identifier: Which is used to identify your application for the store only.(e.g com.Yourcompanyname)</li>
                                <li>Create a Certificate Signing Request: Used to publish your application on the store.</li>
                                <li>Create an App Store Production Certificate. </li>
                                <li>Create a Production Provisioning Profile. </li>
                                <li>For step 3,4,5 please check this link : <a class="doclink" href="https://clearbridgemobile.com/how-to-create-a-distribution-provisioning-profile-for-ios/" target="_blank">https://clearbridgemobile.com/how-to-create-a-distribution-provisioning-profile-for-ios/</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="g-section side-body-section" id="mobios3">
                        <h3 class="g-section-heading">Create a Release Build.</h3>
                        <div class="g-section-content">
                            <ul>
                                <li>Open your project into Xcode. </li>
                                <li>Check bundle identifier, bundle version allowed orientations, application supported minimum version, Proper distribution profile.</li>
                                <li>Select the product from the top menu.</li>
                                <li>Choose a clean project from product. It will just clean your project</li>
                                <li>Choose Archive option.</li>
                                <li>Then select Next from the opened dialogue without selecting anything from dialogue just hit NEXT.</li>
                                <li>Choose provisioning profile certificate </li>
                                <li>Hit Next button it will process and make one build (.ipa)</li>
                            </ul>
                        </div>
                    </div>
                    <div class="g-section side-body-section" id="mobios4">
                        <h3 class="g-section-heading">Generate Map API key.</h3>
                        <div class="g-section-content">
                            <ul>
                                <li>Go to the Google Cloud Platform Console.</li>
                                <li>Click the project drop-down and select or create the project for which you want to add an API key.</li>
                                <li>Click the menu button <img src="<?php echo base_url();?>/assets/front/images/toggle.png"> and select APIs.</li>
                                <li>From the list, enable Maps SDK for iOS, Maps SDK for Android and Places API</li>
                                <li>From the list in left side menu, click Credentials. On the right side, there will be an option of Create Credentials at top. </li>
                                <li>After that, there will be 4 options in the dropdown. Select API Key from it and a new API key will be created. </li>
                                <li>You can rename the API key to the name of your project. The API keys section displays your newly created API key.</li>
                            </ul>
                        </div>
                    </div>
                    <div class="g-section side-body-section" id="mobios5">
                        <h3 class="g-section-heading">Firebase Setup</h3>
                        <div class="g-section-content">
                            <ul>
                                <li>If you have firebase integration then follow below steps</li>
                                <li>Open firebase console. Create your project</li>
                                <li>Add iOS project setup with your bundle identifier and generate GoogleService-Info.plist file</li>
                                <li>Replace your new GoogleService-Info.plist file with the existing one placed inside app folder</li>
                                <li>Also replace the “Server key” from firebase console project setting to backend server.</li>
                                <li>Follow the steps <a href="https://firebase.google.com/docs/cloud-messaging/ios/client" class="doclink" target="_blank">https://firebase.google.com/docs/cloud-messaging/ios/client</a> from this.</li>
                                <li>For xcode setup – Open your project in xcode select your project target</li>
                                <li>Select your project from left hand side and select capabilities from top</li>
                                <li>Turn on Push notification from listed list, if proper steps you follow on firebase then it will show 2 right tick mark in new Xcode it will just toggle button.</li>
                            </ul>
                        </div>
                    </div>

                    <div class="g-section side-body-section" id="readme1">
                        <h3 class="g-section-heading">Support Pre-Requisites:</h3>
                        <div class="g-section-content">
                            <ul>
                                <h6>Many thanks for your purchase.</h6>
                                <p>We’re committed to giving you the best support, and so we have worked on a solution for you.</p>
                                <li>
                                    <strong>You should have following set-up ready with you in your System:</strong>
                                    <ol>
                                        <li>Android Studio Complete Setup </li>
                                        <li>Xcode Code Support Path Set-up </li>
                                        <li>Visual Studio Set-up </li>
                                        <li>Node Set-Up (Please Find Reference: <a href="https://facebook.github.io/react- native/docs/getting-started.html" target="_blank" class="doclink">https://facebook.github.io/react- native/docs/getting-started.html</a>)</li>
                                        <li>React Native: (Please Find Reference :<a href="https://facebook.github.io" target="_blank" class="doclink"></a>)</li>
                                        <li>Open/imports projects in appropriate IDE. </li>
                                    </ol>
                                </li>
                                <p>If you wish/want that our technical team support Install the Set-up for you, we would be happy to do that and that will be a paid support. </p>
                                <h6>Total Cost you need to pay is 180 USD for Installation support. </h6>
                                <p>After Payment, our team will start deploying the App via Remote Access; we would like to make sure that you perform the following Pre-requisite.</p>
                                <li>
                                    <strong>You should have following details to make your app live in store</strong>
                                    <ol>
                                        <li><strong>LOGO: ‘1024 X 1024’ </strong>PNG Format</li>
                                        <li>PSD/AI for Splash Screen or a PNG image for it <strong>‘1242 X 2208’</strong> resolution</li>
                                        <li>Apple developer account enrolled in Apple Developer Membership. If the client cannot provide that, then we will need agent access </li>
                                        <li>Google Play account. If the client cannot provide that, we will need Admin Access and Firebase Account Credential and Server Key.</li>
                                        <li>Store metadata information: ‘App Name,’ ‘Description,’ ‘Keywords,’ ‘Marketing URL,’ Privacy Policy URL,’ ‘Contact Details’ (Full Name, Phone, E-Mail, Complete Address.)</li>
                                        <li>App category</li>
                                        <li>Google Maps API key.</li>
                                        <li>Short & long description for uploading App in play store.</li>
                                        <li>Selected images to be uploaded.</li>
                                        <li>For iOS, the UID must be appropriately linked.</li>
                                        <li>CPanel credential must support to his CI.</li>
                                        <li>If payment gateway integration, then needs to support React Native CLI.</li>
                                    </ol>
                                </li>
                                <h6>Note:</h6>
                                <li>If you’re not able to follow these pre-requisites, you need to request for additional hours paid support. You need to send us a request for paid support, and we will send you a link to purchase the paid hours. </li>
                                <li>Our Technical team will do the App Deployment for you after you pay for paid support. </li>
                                <li>To Get Installation Support, you need to provide Remote Access. If you fail to provide remote access, we will not be liable to refund the money paid for Installation. </li>   
                                <li>Before purchase, please ask for documentation from the support team. If you have any questions related to support, please contact to support team on <a href="mailto:support@eatanceapp.com" class="doclink" target="_blank">support@eatanceapp.com</a>.</li>
                            </ul>
                        </div>
                    </div>

                    <div class="g-section side-body-section" id="faq">
                        <h3 class="g-section-heading">Do you have Multi Restaurant App?</h3>
                        <a href="#top" class="top-scroll-btn">Back to Top <i class="fas fa-angle-up"></i></a>
                        <div class="g-section-content">
                            <p>We do have Single Restaurant App and Multi restaurant app on Codecanyon.</p>
                            <p>Single Restaurant App: <a href="https://codecanyon.net/item/eatance-restaurant-and-food-delivery-app/23787837" class="doclink" target="_blank">https://codecanyon.net/item/eatance-restaurant-and-food-delivery-app/23787837</a></p>
                            <p>Multi Restaurant App: <a href="https://codecanyon.net/item/eatance-driver-the-food-delivery-and-driver-app/24623948" class="doclink" target="_blank">https://codecanyon.net/item/eatance-driver-the-food-delivery-and-driver-app/24623948</a> </p>
                        </div>
                    </div>
                    <div class="g-section side-body-section">
                        <h3 class="g-section-heading">Do you have Restaurant Mobile App?</h3>
                        <a href="#top" class="top-scroll-btn">Back to Top <i class="fas fa-angle-up"></i></a>
                        <div class="g-section-content">
                            <p>We do not have Restaurant/Vendor Mobile app. For Quotation, please contact us on <a href="mailto:support@evincemage.com" class="doclink" target="_blank">support@evincemage.com</a>.</p>
                        </div>
                    </div>
                    <div class="g-section side-body-section">
                        <h3 class="g-section-heading">Which Technology stack used for Mobile app and Backend?</h3>
                        <a href="#top" class="top-scroll-btn">Back to Top <i class="fas fa-angle-up"></i></a>
                        <div class="g-section-content">
                            <p>The technology stack we have used is Backend PHP CodeIgniter (V3) and React Native Version: 0.60.5 .</p>
                        </div>
                    </div>
                    <div class="g-section side-body-section">
                        <h3 class="g-section-heading">What I’ll get with Single Restaurant code purchase from Codecanyon?</h3>
                        <a href="#top" class="top-scroll-btn">Back to Top <i class="fas fa-angle-up"></i></a>
                        <div class="g-section-content">
                            <p>You will get source code of Admin Panel for Single Restaurant and Source Code Of Customer app (Android & iOS).</p>
                        </div>
                    </div>
                    <div class="g-section side-body-section">
                        <h3 class="g-section-heading">What I’ll get with Multi Restaurant code purchase from Codecanyon?</h3>
                        <a href="#top" class="top-scroll-btn">Back to Top <i class="fas fa-angle-up"></i></a>
                        <div class="g-section-content">
                            <p>You will get Source code Of Admin Panel for Multi Restaurant and Source code of Customer app (Android & iOS) and Driver app (Android & iOS).</p>
                        </div>
                    </div>
                    <div class="g-section side-body-section">
                        <h3 class="g-section-heading">With my Multi Restaurant Source code purchase will every restaurant have its own dashboard to manage their orders?</h3>
                        <a href="#top" class="top-scroll-btn">Back to Top <i class="fas fa-angle-up"></i></a>
                        <div class="g-section-content">
                            <p>Yes, Each Restaurant will have its own dashboard to manage items, food menu, pricing and images. From Super admin you can add user as a Admin user to create Restaurant admin.</p>
                        </div>
                    </div>
                    <div class="g-section side-body-section">
                        <h3 class="g-section-heading">Which Payment Gateway method you have used in APP?</h3>
                        <a href="#top" class="top-scroll-btn">Back to Top <i class="fas fa-angle-up"></i></a>
                        <div class="g-section-content">
                            <p>App do have Cash on Delivery Method. App do not have any integrated Payment gateway. Our technical support team can integrate React native SDK supported Payment Gateway. For more details, please contact our Support team on <a href="mailto:support@evincemage.com" class="doclink">support@evincemage.com</a>.</p>
                        </div>
                    </div>
                    <div class="g-section side-body-section">
                        <h3 class="g-section-heading">Should I buy a developer to change the logo, company name and other things or I can change it directly from the admin panel? </h3>
                        <a href="#top" class="top-scroll-btn">Back to Top <i class="fas fa-angle-up"></i></a>
                        <div class="g-section-content">
                            <p>After purchase, you need to change branding, App name, and Content, images. You cannot use the content, images and app name anywhere to promote your product. You can hire our team for branding changes and contact us on <a href="mailto:support@evincemage.com" class="doclink">support@evincemage.com</a>.</p>
                        </div>
                    </div>
                    <div class="g-section side-body-section">
                        <h3 class="g-section-heading">Which PHP version?</h3>
                        <a href="#top" class="top-scroll-btn">Back to Top <i class="fas fa-angle-up"></i></a>
                        <div class="g-section-content">
                            <p>it will work on PHP 5.6, 7.0 7.1, 7.2.</p>
                        </div>
                    </div>
                    <div class="g-section side-body-section">
                        <h3 class="g-section-heading">Which Database You are using?</h3>
                        <a href="#top" class="top-scroll-btn">Back to Top <i class="fas fa-angle-up"></i></a>
                        <div class="g-section-content">
                            <p>We are using mysql database.</p>
                        </div>
                    </div>
                    <div class="g-section side-body-section">
                        <h3 class="g-section-heading">Does Demo Data included (Demo data like on our app)?</h3>
                        <a href="#top" class="top-scroll-btn">Back to Top <i class="fas fa-angle-up"></i></a>
                        <div class="g-section-content">
                            <p>We will supply workflow document via email. You should review that before your purchase.</p>
                        </div>
                    </div>
                    <div class="g-section side-body-section">
                        <h3 class="g-section-heading">Does it have 3rd party services?</h3>
                        <a href="#top" class="top-scroll-btn">Back to Top <i class="fas fa-angle-up"></i></a>
                        <div class="g-section-content">
                            <p>Yes We are using these Third party services listed below</p>
                            <ul>
                                <li>Google maps </li>
                                <li>Firebase It has Firebase Third party for real time chat and send push notifications, Which is free but limited , after lot of users and usages you have to buy their packages and pricing will on Pay As you go See Firebase packages</li>
                            </ul>
                        </div>
                    </div>
                    <div class="g-section side-body-section">
                        <h3 class="g-section-heading">Does it have Admin panel</h3>
                        <a href="#top" class="top-scroll-btn">Back to Top <i class="fas fa-angle-up"></i></a>
                        <div class="g-section-content">
                            <p>Yes It has Three (Super Admin, Customer App and Delivery Boy App).</p>
                        </div>
                    </div>
                    <div class="g-section side-body-section">
                        <h3 class="g-section-heading">Does it have Documentation</h3>
                        <a href="#top" class="top-scroll-btn">Back to Top <i class="fas fa-angle-up"></i></a>
                        <div class="g-section-content">
                            <p>Yes it has Documentation about Cpanel Guidelines, Admin Panel Setup, Android App Setup , firebase push notification setup.</p>
                        </div>
                    </div>
                    <div class="g-section side-body-section">
                        <h3 class="g-section-heading">Which framework we are using for API</h3>
                        <a href="#top" class="top-scroll-btn">Back to Top <i class="fas fa-angle-up"></i></a>
                        <div class="g-section-content">
                            <p>We are using CodeIgniter 3.1.9.</p>
                        </div>
                    </div>
                    <div class="g-section side-body-section">
                        <h3 class="g-section-heading">Does it support other languages (RTL)</h3>
                        <a href="#top" class="top-scroll-btn">Back to Top <i class="fas fa-angle-up"></i></a>
                        <div class="g-section-content">
                            <p>This only support only English language.</p>
                        </div>
                    </div>
                    <div class="g-section side-body-section">
                        <h3 class="g-section-heading">API/Server/3rd party services setup will be free?</h3>
                        <a href="#top" class="top-scroll-btn">Back to Top <i class="fas fa-angle-up"></i></a>
                        <div class="g-section-content">
                            <p>No we will not do any help to setup API/server/3rd party services.</p>
                        </div>
                    </div>
                    <div class="g-section side-body-section">
                        <h3 class="g-section-heading">Do you provide customization?</h3>
                        <a href="#top" class="top-scroll-btn">Back to Top <i class="fas fa-angle-up"></i></a>
                        <div class="g-section-content">
                            <p>Yes, we do offer Customization but for that we will charge extra. For any Customization support contact us on our email info@evincedevv.com.</p>
                        </div>
                    </div>
                    <div class="g-section side-body-section">
                        <div class="g-section-content">
                            <h6>Thank you & Support</h6>
                            <p>Many thanks for taking Interest into our Product and I hope we will be able to build successful relationship. If you need support or have some questions. You can visit our support resources here at <a href="mailto:info@evincedev.com">info@evincedev.com</a>.</p>
                            <h6>What support does include </h6>
                            <ul>
                                <li>Answers to (technical) questions about the item's features.</li>
                                <li>Assistance with reported bugs and issues.</li>
                                <li>Please note that support does not include:</li>
                                <li>Customization & adding new features</li>
                                <li>Installation Services</li>
                                <li>Support for 3rd party software and/or plugins</li>
                                <li>Content related rejections and violations from Google</li>
                            </ul>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
</section>

<script type="text/javascript" src="<?php echo base_url();?>assets/admin/plugins/jquery-validation/js/jquery.validate.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/admin/plugins/jquery-validation/js/additional-methods.min.js"></script>
<script src="<?php echo base_url();?>assets/front/js/scripts/admin-management-front.js"></script>

<script type="text/javascript">
        // Select all links with hashes
        $('a[href*="#"]')
            // Remove links that don't actually link to anything
            .not('[href="#"]')
            .not('[href="#0"]')
            .click(function (event) {
                // On-page links
                if (
                    location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '')
                    &&
                    location.hostname == this.hostname
                ) {
                    // Figure out element to scroll to
                    var target = $(this.hash);
                    target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                    // Does a scroll target exist?
                    if (target.length) {
                        // Only prevent default if animation is actually gonna happen
                        event.preventDefault();
                        var header = $('.header-area').outerHeight();
                        $('html, body').animate({
                            scrollTop: target.offset().top - header
                        }, 1000);
                    }
                }
                if ($(window).width() <= 767) {
                    $('html, body').animate({
                        scrollTop: target.offset().top
                    }, 1000);
                }
            });

        $('.help-sidebar-list li').on('click', function () {
            $(this).closest('.help-sidebar-list').find('li').removeClass('active');
            $(this).addClass('active');
            // $('.help-sidebar-list li.active').removeClass('active');
            
            $('.help-sidebar-list .scroll-top-btn').removeClass('active');
        });

    </script>

<?php $this->load->view('footer'); ?>