var express = require('express');
var fs = require('fs');

express()
  .get('*', function(req, response) {
    fs.readFile(__dirname + '/public/' + req.query.file, 'utf8', function (err, data) {
      response.set('Content-Type', 'application/xml; charset=utf-8');
      response.send(data);
    });
  })
  .listen(3000);
