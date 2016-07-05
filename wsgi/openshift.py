#!/usr/bin/env python
# coding: utf-8
import sys
import json
import tornado.web
from fm.handlers import FmHomeHandler
class MainHandler(tornado.web.RequestHandler):
    def get(self):
        res = ['%s----->%s\n' % (key, val) for key, val in sorted(sys.modules.items())]
        self.set_header('Content-Type', 'text/plain')
        self.write('\n'.join(res))
        # self.write(tornado.version)
        # self.write(json.dumps(sys.modules))
        # self.render('index.html')

# Put here yours handlers.

handlers = [
    (r'/', MainHandler),
    (r'/fm', FmHomeHandler)
]
