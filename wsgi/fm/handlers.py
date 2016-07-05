#!/usr/bin/env python
# coding: utf-8
import os
# import httplib
import json
from tornado.web import RequestHandler
from tornado.escape import native_str, json_decode
# from tornado.curl_httpclient import CurlError
# from tornado.httpclient import HTTPError
# from utils.httpclient import AsyncHTTPClient
# from xml.dom import minidom as xmlparse
# from models import DB_Session

# class BaseHandler(RequestHandler):
#     def initialize(self):
#         self.session = DB_Session()

#     def on_finish(self):
#         self.session.close()

class FmHomeHandler(RequestHandler):
	def get(self):
		# res = ['%s----->%s\n' % (key, val) for key, val in sorted(os.environ.items())]
		# self.set_header('Content-Type', 'text/plain')
		# self.write('\n'.join(res))
		self.render('fm/index.html')
	def post(self):
		action = self.get_argument('a')
		if not action:
			self.write({'error': u'非法请求'})
		elif action == 'radio':
			lst = [[u'测试3']]
			# http_client = AsyncHTTPClient()
			# try:
			#     req = yield http_client.fetch(url = 'http://www.xiami.com/radio/xml/type/4/id/6961722', headers = {'User-Agent':'Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko'})
			# except (CurlError, HTTPError):
			#     self.write('链接失败')
			# body = native_str(req)
			# self.write(body)
			# http_client = httplib.HTTPConnection('www.xiami.com', 80, timeout = 30)
			# http_client.request('GET', '/radio/xml/type/4/id/6961722')
			# r = http_client.getresponse()
			# # for item in r.getheaders():
			# #     if item[0] == 'content-type':
			# #         self.set_header('Content-Type', item[1])
			# # self.write(r.read())
			# dom = xmlparse.parseString(r.read())
			# root = dom.documentElement
			# trackList = root.getElementsByTagName('track')
			# lst = []
			# for index,track in enumerate(trackList):
			#     song_id     = track.getElementsByTagName('song_id')[0].childNodes[0].data
			#     title       = track.getElementsByTagName('title')[0].childNodes[0].data
			#     pic         = track.getElementsByTagName('pic')[0].childNodes[0].data
			#     mp3         = track.getElementsByTagName('location')[0].childNodes[0].data
			#     album_name  = track.getElementsByTagName('album_name')[0].childNodes[0].data
			#     artist      = track.getElementsByTagName('artist')[0].childNodes[0].data
			#     album_id    = track.getElementsByTagName('album_id')[0].childNodes[0].data
			#     # try:
			#     #     length  = track.getElementsByTagName('length')[0].childNodes[0].data
			#     # except:
			#     #     length  = get_song_length(song_id)
			#     # try:
			#     #     play    = self.session.query(PlayCountModel).filter_by(xid = song_id).first().play
			#     # except:
			#     #     play    = 0
			#     # self.session.close()
			#     # [["1772432260","\u5343\u672c\u685c\"Session ver\"","http:\/\/img.xiami.net\/images\/album\/img18\/92218\/878688111387868811_1.jpg","4h%2Ff.moF%2%78272519Em3teD979fE%95d11%55ut3Fmixim222281F22E22_pFhy7fae3656E1475EEltA%5li.%1F1F61146_7%l3a_%1128%aEae754E--lp%2.eac289888%73%165.%uk3aaf85e4%d-14%%n","\u79c1\u7acb\u99d2\u6ca2\u5b66\u5712","\u3053\u307e\u3093","87868811"]]
			#     lst.append([
			#         int(song_id),
			#         title,
			#         pic, #pic.replace('http://img.xiami.net/images/album/', ''),
			#         mp3,
			#         album_name,
			#         artist,
			#         int(album_id)
			#     ])
		elif action == 'song':
			xid = self.get_argument('xid')
			lst = [[xid,u'测试2']]
		self.write(json.dumps(lst))
