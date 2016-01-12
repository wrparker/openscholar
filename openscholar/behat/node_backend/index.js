var express = require('express');
var http = require("http");
var fs = require('fs');

express().get('*', function(req, response) {
  fs.readFile(__dirname + '/public/' + req.query.file, 'utf8', function (err, data) {

    if (req.query.file == 'courses.xml') {
      response.set('Content-Type', 'application/xml; charset=utf-8');
    }

    if (req.query.file == '56.json') {
      response.set('Content-Type', 'application/json; charset=utf-8');

      if (req.query.hide_address != null) {
        response.send(JSON.stringify({"field_address": null}));
      }
      else {
        http.get('http://127.0.0.1:8888/?q=obama/node/56.json', function(res) {

          // Buffer the body entirely for processing as a whole.
          var bodyChunks = [];
          res.on('data', function(chunk) {
            bodyChunks.push(chunk);
          }).on('end', function() {
            response.send(Buffer.concat(bodyChunks));
          });
        });
      }

      return;
    }

    response.send(data);
  });
})
.listen(3000);
