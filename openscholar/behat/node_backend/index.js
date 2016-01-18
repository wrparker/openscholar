var express = require('express');
var http = require("http");
var fs = require('fs');
var NodeCache = require( "node-cache" );
var cache = new NodeCache();

express().get('*', function(req, response) {
  fs.readFile(__dirname + '/public/' + req.query.file, 'utf8', function (err, data) {

    if (req.query.file == 'courses.xml') {
      response.set('Content-Type', 'application/xml; charset=utf-8');
    }

    if (req.query.file == '56.json') {

      if (req.query.empty_address == 1) {
        response.send(JSON.stringify({'field_address': ''}));
        return;
      }

      response.set('Content-Type', 'application/json; charset=utf-8');

      cache.get('cachedResult', function(err, value) {
        if (err) {
          return;
        }
        if (value == undefined) {
          http.get('http://127.0.0.1:8888/?q=obama/node/56.json', function(res) {

            var bodyChunks = [];
            res
              .on('data', function(chunk) {
                bodyChunks.push(chunk);
              })
              .on('end', function() {
                cache.set('cachedResult', Buffer.concat(bodyChunks).toString());
                response.send(Buffer.concat(bodyChunks));
              });
          });
        }
        else {
          response.send(value);
        }
      });

      return;
    }

    response.send(data);
  });
})
.listen(3000);
