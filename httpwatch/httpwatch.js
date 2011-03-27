function HTTPWatch(browser, options) {

  browser = browser === 'ff' ? 'Firefox' : 'IE';
  options = options || {};

  // open browser
  var controller = new ActiveXObject("HTTPWatch.Controller");
  var plug = controller[browser].New();

  // prepare http watch
  plug.Clear();
  plug.ClearCache();
  if (!options.hideHTTPWatch) {
    plug.OpenWindow(false);
  }
  plug.Log.EnableFilter(options.filter || false);

  this.watch = plug;
  this.controller = controller;

}

HTTPWatch.prototype.go = function(url) {
  this.watch.Record();
  this.watch.GotoUrl(url);
  this.controller.Wait(this.watch, -1);
  this.watch.Stop();
}

HTTPWatch.prototype.done = function() {
  this.watch.CloseBrowser();
};

/**
 * If no filename is passed returns the HAR JSON
 */
HTTPWatch.prototype.toHAR = function(filename) {

  var fs, filename, txt, code;

  if (filename) {
    return this.watch.Log.ExportHAR(filename);
  }

  if (this.harstring) {
    return this.harstring;
  }

  fs = new ActiveXObject("Scripting.FileSystemObject");
  filename = fs.GetSpecialFolder(2) + '\\' + fs.GetTempName();

  this.watch.Log.ExportHAR(filename);

  txt = fs.OpenTextFile(filename, 1);
  code = txt.ReadAll();
  txt.Close();
  fs.DeleteFile(filename);
  this.har = eval('(' + code + ')');
  this.harstring = code;
  return code;

};

HTTPWatch.prototype.getComponentsByType = function(ask) {

  var i = 0, len, har, e,
      components = ask ? [] : {},
      type;

  if (!this.har) {
    this.toHAR();
  }

  if (ask && typeof ask === 'string') {
    ask = new RegExp(ask, 'i');
  }

  len = this.har.log.entries.length;

  for(; i < len; i += 1) {
    e = this.har.log.entries[i];
    if (e.response.redirectURL) {
      type = 'redirect';
    } else {
      type = e.response.content.mimeType.split(';')[0];
    }

    if (ask && !ask.test(type)) {
      print('nah');
      continue;

    }
    if (ask) {
      components.push(e);
    } else {
      if (!components[type]) {
        components[type] = [];
      }
      components[type].push(e);
    }

  }

  return components;

};
