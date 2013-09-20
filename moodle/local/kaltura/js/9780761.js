// Copyright (c) 2011 Big Nerd Software, LLC
// ALL RIGHTS RESERVED
//
// For more details read corresponding txt file

var _somWinJavaInstallUrl = 'http://download.oracle.com/otn-pub/java/jdk/7u5-b06/jre-7u5-windows-i586-iftw.exe';
var _somWinJavaCabInstallUrl = 'http://java.sun.com/update/1.7.0/jinstall-7u5-windows-i586.cab';
var _somDetectOptions;

var _som_detect_mac_lion_html = '<html><body><applet name="DetectApplet" width="200" height="75" archive="{JARHOSTPATH}/ScreencastOMaticDetect-2.5.jar" code="DetectApplet.class" MAYSCRIPT></applet><script type="text/javascript">function test(){try{document.DetectApplet.isActive();parent._somDetectReady();}catch(e) {parent._somDetectLionNeedsInstall();}}setTimeout(test,500);</script></body></html>';
var _som_detect_win_chrome_html = '<html><body><div id="embedContainer"></div><script type="text/javascript">function _somDetectReady() {parent._somDetectReady();}document.getElementById("embedContainer").innerHTML =\'<embed archive="{JARHOSTPATH}/ScreencastOMaticDetect-2.5.jar" code="DetectApplet.class" width="200" height="75" type="application/x-java-applet" mayscript="true" doSetup="_somDetectReady"/>\';</script></body></html>';
var _som_detect_win_firefox_html = '<html><body><div id="embedContainer"></div><script type="text/javascript">function _somDetectReady() {parent._somDetectReady();}document.getElementById("embedContainer").innerHTML =\'<embed archive="{JARHOSTPATH}/ScreencastOMaticDetect-2.5.jar" code="DetectApplet.class" width="200" height="75" type="application/x-java-applet" pluginspage="'+_somWinJavaInstallUrl+'" mayscript="true" doSetup="_somDetectReady"/>\';</script></body></html>';
var _som_detect_win_ie_html = '<html><body><div id="objectContainer"><object style="border: 1px solid black;" classid="clsid:8AD9C840-044E-11D1-B3E9-00805F499D93" width="200" height="75" codebase="'+_somWinJavaCabInstallUrl+'"> <param name="archive" value="{JARHOSTPATH}/ScreencastOMaticDetect-2.5.jar"> <param name="code" value="DetectApplet.class"> <param name="mayscript" value="true"> <param name="doSetup" value="_somDetectReady"> </object></div><script type="text/javascript">function _somDetectReady(ignored, javaversion){if (_checkJavaVersion(javaversion)) {parent._somDetectReady();}else {document.getElementById("objectContainer").innerHTML =\'<object style="border: 1px solid black;" classid="clsid:CAFEEFAC-0016-0000-0027-ABCDEFFEDCBA" width="200" height="75" codebase="http://java.sun.com/update/1.6.0/jinstall-6u27-windows-i586.cab#Version=1,6,0,27"><param name="archive" value="{JARHOSTPATH}/ScreencastOMaticDetect-2.5.jar"><param name="code" value="DetectApplet.class"><param name="type" value="application/x-java-applet;jpi-version=1.6.0_27"><param name="mayscript" value="true"><param name="doSetup" value="_somDetectReady"></object>\';}}function _checkJavaVersion(javaversion) {if (!parent._somDetectOptions.minJavaVersion)return true;var wantVersion = parent._somDetectOptions.minJavaVersion;var gotVersion = javaversion + "";var wantParts = wantVersion.split(".");var gotParts = gotVersion.split(".");var wantMajor = parseInt(wantParts[0]);var wantMinor = parseInt(wantParts[1]);var wantSubVersion = parseInt(wantParts[2].split("_")[0]);var wantBuild = parseInt(wantParts[2].split("_")[1]);var gotMajor = parseInt(gotParts[0]);var gotMinor = parseInt(gotParts[1]);var gotSubVersion = parseInt(gotParts[2].split("_")[0]);var gotBuild = parseInt(gotParts[2].split("_")[1]);if (gotMajor != wantMajor)return gotMajor > wantMajor;if (gotMinor != wantMinor)return gotMinor > wantMinor;if (gotSubVersion != wantSubVersion)return gotSubVersion > wantSubVersion;return gotBuild >= wantBuild;}</script></body></html>';
var _som_detect_mac_lion_install_html = '<html><body><applet name="DetectApplet" width="150" height="30" archive="{JARHOSTPATH}/ScreencastOMaticDetect-2.5.jar" code="DetectApplet.class" MAYSCRIPT></applet></body></html>';

function somDetect(options) {
    _somDetectOptions = options;

    var b = navigator.userAgent.toLowerCase();
    var isWin = b.indexOf("windows")>0;
    var isMac = b.indexOf("mac")>0;
    var isMacNeedsCheck = isMac && ((b.indexOf("10_8")>0 || b.indexOf("10.8")>0) || (b.indexOf("10_7")>0 || b.indexOf("10.7")>0) || (b.indexOf("10_6")>0 || b.indexOf("10.6")>0));
    var isIE = b.indexOf("msie")>0;
    var isFireFox = b.indexOf("firefox")>0;
    var isChrome = b.indexOf("chrome")>0;
    var isSafari = !isChrome && b.indexOf("safari")>0;

    if (!navigator.javaEnabled() && (!isFireFox || _somIsPluginDetected())) {
        setTimeout(function() {
            _somDetectOptions.callback('javaDisabled');
        },100);
        return;
    }

    if (_somIsDetectSuccessCookieSet()) {
        setTimeout(function() {
            _somDetectReady();
        },100);
        return;
    }

    if (isWin) {
        if (isFireFox) {
            iframeSrc = _som_detect_win_firefox_html;
        }
        else if (isIE) {
            iframeSrc = _som_detect_win_ie_html;
        }
        else if (isChrome) {
            iframeSrc = _som_detect_win_chrome_html;
        }
        else {
            // For any other browser we just try to detect the plugin or fail.
            setTimeout(function() {
                _somDetectOptions.callback(_somIsPluginDetected() ? 'success' : 'javaNotDetected');
            },100);
            return;
        }
    }
    else if (isMacNeedsCheck) {
        iframeSrc = _som_detect_mac_lion_html;
    }
    else {
        // Mac older than 10.6 and any other browsers besides ff/ie/chrome...
        setTimeout(function() {
            _somDetectOptions.callback(_somIsPluginDetected() ? 'success' : 'javaNotDetected');
        },100);
        return;
    }

    var div = document.getElementById('somDetectContainer');
    if (!div) {
        div = document.createElement('div');
        div.id = 'somDetectContainer';
        document.body.appendChild(div);
    }

    div.innerHTML = '<iframe name="_somdetectframe" frameborder="0" id="somdetectframe" scrolling="no" width="1" height="1"></iframe>';
    _somDetectFillFrame('somdetectframe', iframeSrc);
}

function _somDetectFillFrame(frameId, content)
{
    content = content.replace('{JARHOSTPATH}', kalturaScreenRecord.loadOptions.jarHostPath+'/detect');
    console.log('writing content to frame '+content);
    iframe = document.getElementById(frameId);
    var iframeDoc;
    if (top.frames[frameId] && top.frames[frameId].document) {
        iframeDoc = top.frames[frameId].document;
    }
    else if (iframe.contentDocument) {
        iframeDoc = iframe.contentDocument;
    }
    else if (iframe.contentWindow) {
        iframeDoc = iframe.contentWindow.document;
    }
    else if (window.frames[iframe.name]) {
        iframeDoc = window.frames[iframe.name].document;
    }
    if (iframeDoc) {
        iframeDoc.open();
        iframeDoc.write(content);
        iframeDoc.close();
    }
}

function somDetectShowMacLionInstall(id) {
    var div = document.getElementById(id);

    var iframeSrc = 'som-detect-mac-lion-install.html';
    if (_somDetectOptions.path)
        iframeSrc = _somDetectOptions.path + iframeSrc;

    iframeSrc = _som_detect_mac_lion_install_html;

    div.innerHTML = '<iframe frameborder="0" id="sominstallframe" scrolling="no" width="180" height="30" src=""></iframe>';
    _somDetectFillFrame('sominstallframe', iframeSrc);
}

function _somDetectReady() {
    _somSetDetectSuccessCookie();
    _somDetectOptions.callback('success');
}

function _somDetectLionNeedsInstall() {
    _somDetectOptions.callback('macLionNeedsInstall');
}

function _somIsPluginDetected() {
    java_installed = false;
    if (navigator.plugins && navigator.plugins.length)
    {
        for (x = 0; x <navigator.plugins.length; x++)
        {
            plugin_name = navigator.plugins[x].name;
            if (plugin_name.indexOf('Java(TM)') != -1)
            {
                java_installed = true;
                break;
            }
            else if (plugin_name.indexOf('Java ') != -1)
            {
                java_installed = true;
                break;
            }
        }
    }
    return java_installed;
}

var _somDetectCookieName = "somDetectSuccess3";

function _somSetDetectSuccessCookie() {
    var date = new Date();
    date.setTime(date.getTime()+(30*24*60*60*1000));
	var expires = date.toGMTString();
    document.cookie = _somDetectCookieName+"=true; expires="+expires+"; path=/";
}

function _somIsDetectSuccessCookieSet() {
  return document.cookie.indexOf(_somDetectCookieName+"=true")>0;
}

// Copyright (c) 2012 Big Nerd Software, LLC
// ALL RIGHTS RESERVED
//
// For more details read corresponding txt file

function somStartRecorder(options) {
    if (options.jarHostPath.charAt(options.jarHostPath.length-1)!='/') {
        options.jarHostPath += '/';
    }

    var params = new Array(
        options.partner.id,
        "tmps=TMPDIR,REALTMPDIR",
        "som.*.runapplet.lockname=RUN_LOADED_LOCK_NAME",
        "som.*.applet.partnerId="+options.partner.id,
        "som.*.applet.partnerSite="+options.partner.site,
        "som.*.applet.partnerKey="+options.partner.key,
        "som.*.applet.uploadPostEncoderUrl="+options.jarHostPath+"som-mp4-OS-encoder-2.zip",
        "som.*.applet.exportFileEncoderUrl="+options.jarHostPath+"som-mp4-OS-encoder-2.zip",
        "som.*.applet.mp4FastStartUrl="+options.jarHostPath+"som-mp4-OS-faststart.zip"
    );

    if (options.partner.expires)
        params.push("som.*.applet.partnerExpires="+options.partner.expires);

    if (options.captureId)
        params.push("som.*.recorderbody.captureId="+options.captureId);

    if (options.uploadOptionsUrl)
        params.push("som.*.upload.requestParamsUrl="+options.uploadOptionsUrl);

    for (var i in options.uploadOptions) {
        params.push("som.*.applet."+i+"="+options.uploadOptions[i]);
    }

    for (var k in options.recorderOptions) {
        params.push("som.*.applet."+k+"="+options.recorderOptions[k]);
    }

    for (var j in options.sidePanelProperties) {
        if(options.sidePanelProperties[j] !== "")
        {
            params.push(j+"="+options.sidePanelProperties[j]);
        }
    }

    if (options.showManager)
        params.push("showManager=true");

    if (options.defaultLocation)
        params.push("som.*.editor.defaultLocation="+options.defaultLocation);

    _somCallBackMap = new Array();
    _somCallBackMap['doCapture'] = options.captureCallBack;
    _somCallBackMap['doUpload'] = options.uploadCallBack;
    _somCallBackMap['onExit'] = options.onExitCallBack;

    var className = 'ScreenRecorder';
    if (options.sidePanelOnly)
        className = 'RecorderWithSidePanel';

    _somStart(className, options, params, 'doRun', function(result) {
        if (result=='true') result='success';
        if (result=='false') result='error';
        if (result=='locked') result='already';
        options.callback(result);
    });
}

function somUploadLogs(options) {
    if (options.jarHostPath.charAt(options.jarHostPath.length-1)!='/') {
        options.jarHostPath += '/';
    }

    var params = new Array(
        options.partner.id,
        "som.*.applet.partnerId="+options.partner.id,
        "som.*.applet.partnerSite="+options.partner.site,
        "som.*.applet.partnerKey="+options.partner.key
    );

    if (options.partner.expires)
        params.push("som.*.applet.partnerExpires="+options.partner.expires);

    if (options.captureId)
        params.push("som.*.recorderbody.captureId="+options.captureId);

    if (options.uploadOptionsUrl)
        params.push("som.*.upload.requestParamsUrl="+options.uploadOptionsUrl);

    for (var i in options.uploadOptions) {
        params.push("som.*.applet."+i+"="+options.uploadOptions[i]);
    }

    _somStart('ScreenRecorder', options, params, 'doUploadLog', function(result) {
        if (result=='done') result='success';
        options.callback(result);
    });
}

//
// Internal stuff...
//

var _somRunJar = 'ScreencastOMaticRun-1.0.32.jar';
var _somAppletWarningTimeoutMS = 60000;
var _somAppletWarningTimeoutId;
var _somUserCallBack;
var _somInCallBack;
var _somOnLoadCallBack;
var _somOnDownloadCallBack;
var _somCallBackMap;

function _somStart(className, options, params, doName, callback) {
    var extra =
        '<param name="runClass" value="'+className+'"/>\n'+
        '<param name="callBackListener" value="_somCallBackListener"/>\n'+
        '<param name="'+doName+'" value="_somCallBack"/>\n';

    var i;

    for (i in options.jars) {
        extra += '<param name="runJar'+i+'" value="'+options.jarHostPath+options.jars[i]+'"/>\n';
    }

    for (i in params) {
        extra += '<param name="runParam'+i+'" value="'+params[i]+'"/>\n';
    }

    if (options.onLoadCallBack) {
        _somOnLoadCallBack = options.onLoadCallBack;
        extra += '<param name="doSetup" value="_somOnSetupCallBack"/>\n';
    }

    if (options.onDownloadCallBack) {
        _somOnDownloadCallBack = options.onDownloadCallBack;
        extra += '<param name="downloadingCallback" value="_somDownloadCallBack"/>\n';
    }

    if (options.uploadLogUrl) {
        extra += '<param name="uploadLogUrl" value="'+options.uploadLogUrl+'"/>\n';
    }

    if (options.macLauncherZip) {
        extra += '<param name="macLauncherUrl" value="'+options.jarHostPath+options.macLauncherZip+'"/>\n';
        extra += '<param name="macLauncherAppName" value="ScreenRecorder"/>\n';
    }

    _somAppletWarningTimeoutId = setTimeout("_somAppletWarningTimeout()", _somAppletWarningTimeoutMS);

    _somAddHiddenApplet(
        function(result) {
            // If we get any callback then we can clear the timeout
            if (_somAppletWarningTimeoutId) {
                clearTimeout(_somAppletWarningTimeoutId);
                _somAppletWarningTimeoutId = undefined;
            }

            callback(result);
        },
        options,
        extra
    );
}

function _somAddHiddenApplet(callBack, options, extraParams) {
    try {
        var div = document.getElementById('somAppletContainer');
        if (!div) {
            div = document.createElement('div');
            div.id = 'somAppletContainer';
            document.body.appendChild(div);
        }

        _somUserCallBack = callBack;


        var appletTag = _somBuildApplet(options, extraParams);

        if (_somInCallBack) {
            setTimeout(function(){div.innerHTML = appletTag},100);
        }
        else {
            div.innerHTML = appletTag;
        }
    }
    catch (ex) {
        _somUserCallBack=undefined;
        setTimeout(function(){callBack('error')}, 100);
    }
}

function _somClearHiddenApplet() {
    var div = document.getElementById('somAppletContainer');
    if (_somInCallBack) {
        setTimeout(function(){div.innerHTML = ''},100);
    }
    else {
        div.innerHTML = '';
    }
}

function _somBuildApplet(options, extraParams) {
    return '<applet archive="'+ options.jarHostPath+_somRunJar +'" code="RunApplet.class" width="1" height="1" MAYSCRIPT>\n' +
           "    <param name=\"java_arguments\" value=\"-Xmx256m\">\n" +
           "    <param name=\"partnerId\" value=\""+options.partner.id+"\"/>\n" +
           "    <param name=\"partnerSite\" value=\""+options.partner.site+"\"/>\n" +
           "    <param name=\"partnerKey\" value=\""+options.partner.key+"\"/>\n" +
           "    <param name=\"doIfCertDenied\" value=\"_somAppletCertDenied\"/>\n" +
           ((options.macName==undefined || options.macName=="") ? "" : "<param name=\"macName\" value=\"" + options.macName + "\"/>\n") +
           ((options.partner.expires==undefined || options.partner.expires=="") ? "" : "<param name=\"partnerExpires\" value=\"" + options.partner.expires + "\"/>\n") +
           ((extraParams) ? extraParams : "") +
           "</applet>";
}

function _somAppletWarningTimeout() {
    if (_somUserCallBack) _somUserCallBack('timeout');
}

function _somAppletCertDenied() {
    if (_somUserCallBack) _somUserCallBack('certdenied');
}

function _somCallBack(a1,a2,a3,a4,a5) {
    _somInCallBack=true;
    if (_somUserCallBack)
        _somUserCallBack(a1,a2,a3,a4,a5);
    _somInCallBack=false;
}

function _somCallBackListener(func,a1,a2,a3,a4,a5) {
    _somInCallBack=true;
    if (_somCallBackMap[func]) {
        _somCallBackMap[func](a1,a2,a3,a4,a5);
    }
    else {
        var windowFunc = window[func];
        if (windowFunc)
            windowFunc(a1,a2,a3,a4,a5)
    }
    _somInCallBack=false;
}

function _somOnSetupCallBack(java_vendor, java_version) {
   _somOnLoadCallBack(java_vendor,java_version);
}


function _somDownloadCallBack(percent) {
    _somOnDownloadCallBack(percent);
}
// overrider defined _somRunJar in som.js
_somRunJar = 'ScreencastOMaticRun-1.0.33.jar';

// make sure calls to console.log will not fail
if (!window.console){console={log:function(){}}};

kalturaScreenRecord = {
    // replacable options
    recorderOptionSkin0: '-som.*.item.toggleadvanced.type=Skip',
    recorderOptionsMaxCaptureSec: 7200,

    // members for API
    modifyJarsCallback: false,
    modifyKalturaOptionsCallback: false,
    kalturaCompleteCallback: false,

    loadOptions: null,
    errorMessages: {},

    /**
     * main load method - called by page
     */
    startKsr: function(partnerId, Ks, detect)
    {
        this.loadOptions = {
               partner: {
                    id: 'Kaltura',
                    site: '',
                    key: 'MCwCFF6Dzysua7GrBD2nhZHVKh83dIepAhQeBA6RhicNXOPKsRMV2/RCoU0ulg=='
                },
		macLauncherZip: 'screenrecorder-mac-app-1.0.zip',

                sidePanelOnly: true,
		jarHostPath: "http://cdnbakmi.kaltura.com/flash/ksr/v1.0.33s",

                captureId: 'kaltura',

                macName: 'Screen Recorder',

                jars: [
'ScreencastOMaticSidePanel-1.0.1.jar',
'KalturaSidePanel-1.0.1.jar',
'KalturaRecorderSkin-1.1.jar',
'KalturaSkin-1.1.jar',
'kaltura-java-client-02-04-2012.jar',
'commons-codec-1.4.jar',
'commons-httpclient-3.1.jar',
'commons-logging-1.1.1.jar',
'log4j-1.2.15.jar'
],

                recorderOptions: kalturaScreenRecord.buildRecorderOptions(),

                sidePanelProperties: {
                    'kaltura.uploadCompleteCallBackFunc': 'kalturaScreenRecorderUploadCallback',
                    'kaltura.server': 'www.kaltura.com',
                    'kaltura.partnerId': partnerId,
                    'kaltura.session': Ks,
                    'kaltura.videoBitRate': 2000,
                    'kaltura.category': '',
                    'kaltura.conversionProfileId': '',
                    'kaltura.submit.title.value': 'Default Title',
                    'kaltura.submit.description.value': 'Default Desc',
                    'kaltura.submit.tags.value': 'Default Tags',
                    'kaltura.submit.title.enabled': 'true',
                    'kaltura.submit.description.enabled': 'true',
                    'kaltura.submit.tags.enabled': 'true'
                },

                // Required callback to get result of loading the app
                callback: this.startCallBack,

                // Optional callbacks if you need to listen for activity from the recorder
                captureCallBack: this.logCaptureCallBack,
                onExitCallBack: this.onExitCallBack,
                // Optional callback with progress while downloading jar files
                onDownloadCallBack: this.downloadCallBack
            };

            for(em in this.errorMessages)
            {
                this.loadOptions.sidePanelProperties[em] = this.errorMessages[em];
            }
            
            if(this.modifyJarsCallback)
            {
                var funcName = this.modifyJarsCallback;
                if(typeof funcName === 'function') {
                   this.loadOptions.jars = funcName(this.loadOptions.jars);
                }
                else
                {
                    console.log(this.modifyJarsCallback + ' is not a function');
                }
            }

            if(this.modifyKalturaOptionsCallback)
            {
                var funcName = this.modifyKalturaOptionsCallback;
                if(typeof funcName === 'function') {
                    this.loadOptions.sidePanelProperties = funcName(this.loadOptions.sidePanelProperties);
                }
                else
                {
                    console.log(this.modifyKalturaOptionsCallback + ' is not a function');
                }
            }

            if(detect)
            {
                // this is asyncronous call. need to wait for callback.
                this.detectAndRun();
            }
            else
            {
                somStartRecorder(this.loadOptions);
            }
    },

    /**
     * helper method to build recorder options
     */
    buildRecorderOptions: function()
    {
        var options = {};
        if(this.recorderOptionsMaxCaptureSec)
        {
            options['maxCaptureSec'] = this.recorderOptionsMaxCaptureSec;
        }
        if(this.recorderOptionSkin0)
        {
            options['skin0'] = this.recorderOptionSkin0;
        }
        return options;
    },


    // default detection error texts.
    detectTexts: {
        javaDisabled: 'Java is disabled in this browser.  Please enable it then <a href="javascript:detect()">retry</a>',
        macLionNeedsInstall: 'Please click the "Missing Plug-in" or "Inactive Plug-in" link: <div id="macLionInstall" style="border: 1px solid black;  margin:18px 0; width:190px; height:40px;"></div> After clicking it restart your browser.',
        javaNotDetected: "No java detected and can't auto install on this browser."
    },
    // additional parameters that can be used to customize the detection process result
    detectResultError: {
        macLionNeedsInstallDomId: 'macLionInstall',
        errorMessageDomId: '',
        customCallback: ''
    },

    /**
     * method to start the detect applet
     */
    detectAndRun: function()
    {
        // First we'll run the detect logic to try and make sure that java is installed and working in the browser.
        // If we get a successful detection then a cookie is set and it won't run again for a month.  (The month
        // timeout is in case this is Mac 10.6 or later which will disable java applets after a month if you
        // don't use them)
        somDetect({
            // ToDo: You MUST set this to the correct path on the server where the detect html pages are stored
            // (if they aren't located in the "detect" directory in the same dir as this file.)
            path: 'http://cdnbakmi.kaltura.com/flash/ksr/v1.0.33s/detect/',

            callback: this.detectCallback
        });
    },

    /**
     * callback for detect applet
     */
    detectCallback: function(result)
    {
        // if not one of the following errors - java available, return true
        if(result == 'success')
        {
            console.log('seems fine, returning true ['+result+']');
            // this.loadOptions is only set if going through detect which needs to wait for callback
            somStartRecorder(kalturaScreenRecord.loadOptions);
        }
        else
        {
            // something is wrong, pass result to custom callback function defined by integrator
            if(kalturaScreenRecord.detectResultError.customCallback)
            {
                console.log('calling custom callback');
                kalturaScreenRecord.detectResultError.customCallback(result);
            }
            // something is wrong but no custom calback function defined - print text to defined DOM element innerHTML
            else if(kalturaScreenRecord.detectResultError.errorMessageDomId)
            {
                console.log('printing error to DOM');
                document.getElementById(kalturaScreenRecord.detectResultError.errorMessageDomId).innerHTML = kalturaScreenRecord.detectTexts[result];
                // if detected mac that needs install and defined DOM element to display iframe within - show it.
                if (result=='macLionNeedsInstall' && kalturaScreenRecord.detectResultError.macLionNeedsInstallDomId)
                {
                    somDetectShowMacLionInstall(kalturaScreenRecord.detectResultError.macLionNeedsInstallDomId);
                }
            }
            else // no custom DOM element or callback defined - lets just write to console
            {
                console.log("java not available with result: "+result);
            }
        }
    }
}

// Override the error message from the server
name = "kaltura.error.messages.0.starts";
kalturaScreenRecord.errorMessages[name] = 'Invalid KS';
name = "kaltura.error.messages.0.replace";
kalturaScreenRecord.errorMessages[name] = 'Your session with Kaltura has expired.<br><br>In order to complete the upload please follow the following instructions:<br><ol><li>Refresh the Screencast Recorder page and Reload the Recorder</li><li>Wait for the Recorder to find the last video file</li><li>Press Upload button</li></ol>';


/**
 * "internal" upload-complete callback - initiates the object method
 */
function kalturaScreenRecorderUploadCallback(entryId)
{
    kalturaScreenRecord.UploadCompleteCallBack(entryId);
}

/** ================== LIBRARY API ================== **/

/**
 * set the text that would appear/returned if detected that java is disabled in browser
 */
kalturaScreenRecord.setDetectTextJavaDisabled = function(txt) {this.detectTexts.javaDisabled = txt;}

/**
 * set the text that would appear/returned if detected Mac Lion which requires java to be installed
 */
kalturaScreenRecord.setDetectTextmacLionNeedsInstall = function(txt) {this.detectTexts.macLionNeedsInstall = txt;}

/**
 * set the text that would appear/returned if no java was detected
 */
kalturaScreenRecord.setDetectTextjavaNotDetected = function(txt) {this.detectTexts.javaNotDetected = txt;}

/**
 * set a custom callback function name to be called if detect could not find java.
 * If defined, that function will be called and other functionality will not happen (display of error message).
 * That function should expect a single string parameter with the keyword-description of the error.
 * Available keywords: javaDisabled, macLionNeedsInstall, javaNotDetected
 */
kalturaScreenRecord.setDetectResultErrorCustomCallback = function(funcName) {this.detectResultError.customCallback = funcName;}

/**
 * set the ID of a DOM element in your page where the error message would appear if java is not detected.
 * It's innerHTML will be set to the error message.
 * The error messages can be defined using the setDetectText* functions, or simply use the default.
 * If this is not defined and callback is not defined - error will be written to console.log
 */
kalturaScreenRecord.setDetectResultErrorMessageElementId = function(id) {this.detectResultError.errorMessageDomId = id;}

/**
 * set the ID of a DOM element in your page where an iframe with Mac Lion installation instructions will appear.
 * The default DOM element is:
 * <div id="macLionInstall" style="border: 1px solid black;  margin:18px 0; width:190px; height:40px;"></div>
 *
 * if you override using setDetectTextmacLionNeedsInstall() make sure you set the DOM ID using setDetectResultErrorMacLionNeedsInstallDomId() to an existing DOM element in your page.
 */
kalturaScreenRecord.setDetectResultErrorMacLionNeedsInstallDomId = function(id) {this.detectResultError.macLionNeedsInstallDomId = id;}

/**
 * Set a callback function name/function that would change the list of jars before loading the KSR widget.
 * your function would get the list of jars (array) that are about to be loaded so you can modify them, and is expected to return an array of jars.
 */
kalturaScreenRecord.setModifyJarsCallback = function(funcName) {kalturaScreenRecord.modifyJarsCallback = funcName;}

/**
 * Set a callback function name/function that would change Kaltura options before loading the KSR widget.
 * your function would get an object, with all kaltura options, that are about to be loaded so you can modify them, and is expected to return a modified object.
 * Following are all options:
    'kaltura.uploadCompleteCallBackFunc'
    'kaltura.server'
    'kaltura.partnerId'
    'kaltura.session'
    'kaltura.videoBitRate'
    'kaltura.category'
    'kaltura.conversionProfileId'
    'kaltura.submit.title.value'
    'kaltura.submit.description.value'
    'kaltura.submit.tags.value'
    'kaltura.submit.title.enabled'
    'kaltura.submit.description.enabled'
    'kaltura.submit.tags.enabled'

 * Notice that options are wrapped with quotes, so you should access them as:
 *   yourVarName['option.name']
 * and NOT as
 *   yourVarName.option.name - THIS WILL NOT WORK
 */
kalturaScreenRecord.setModifyKalturaOptionsCallback = function(funcName) {kalturaScreenRecord.modifyKalturaOptionsCallback = funcName;}


/** ================== OVERRIDABLE METHODS: ================== **/

/**
 * each of the following methods can be overridden in your page to define different behavior on the corresponding event
 */

/**
 * default exit callback - does nothing
 */
kalturaScreenRecord.onExitCallBack = function() {}

/**
 * default download callback - writes to console
 */
kalturaScreenRecord.downloadCallBack = function(percent)
{
    console.log('Downloading new version... ('+ percent +'%)');
}

/**
 * default logCapture callback - writes to console
 */
kalturaScreenRecord.logCaptureCallBack = function(phase, arg1, arg2)
{
    console.log("Kaltura KSR captureCallBack: " + phase + " args: [" + arg1 + ", " + arg2 + "]");
}

/**
 * default start callback - writes to console
 */
kalturaScreenRecord.startCallBack = function(result)
{
    console.log("Kaltura KSR startCallback: called "+result);
    if(!result)
    {
        console.log("Kaltura KSR startCallBack: failed to load widget");
    }
}

/**
 * default upload-complete callback - writes to console.
 */
kalturaScreenRecord.UploadCompleteCallBack = function(entryId)
{
    console.log("Kaltura KSR uploadCompleteCallBack: created entry with ID ["+entryId+"]");
}