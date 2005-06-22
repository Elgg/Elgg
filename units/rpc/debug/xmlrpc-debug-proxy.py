#!/usr/bin/python

"""HTTP debugging proxy

(Presumably) originally by Sam Rushing
    http://www.nightmare.com/medusa/programming.html

Modified by Phillip Pearson <pp@myelin.co.nz>
    http://www.myelin.co.nz/notes/xmlrpc-debug-proxy.html
    (Changes placed in the public domain; do what you will)
     

A very small proxy for HTTP that dumps out what it sees, so you can debug your 
XML-RPC without having to decipher the output from a packet sniffer.

This is basically the proxy used in the Medusa asynchronous sockets tutorial 
(available on http://www.nightmare.com/medusa/programming.html) with a minor 
adjustment to make it flush its buffers before closing any connections.  Without 
that it will drop off important things like </methodResponse> :)

Syntax: xmlrpc-debug-proxy.py <host> <port>

This will listen on port 8000+<port> and proxy through to <host>:<port>

e.g. 'aproxy.py localhost 80' listens on localhost:8080 and proxies through to
     the local web server on port 80.
     
To debug stuff connecting to Radio, run 'xmlrpc-debug-proxy.py localhost 5335' 
and point your scripts at http://localhost:13335/RPC2 (instead of 
http://localhost:5335/RPC2)

"""

import asynchat
import asyncore
import socket
import string

class proxy_server (asyncore.dispatcher):

	def __init__ (self, host, port):
		asyncore.dispatcher.__init__ (self)
		self.create_socket (socket.AF_INET, socket.SOCK_STREAM)
		self.set_reuse_addr()
		self.there = (host, port)
		here = ('', port + 8000)
		self.bind (here)
		self.listen (5)
	
	def handle_accept (self):
		print 'New connection'
		proxy_receiver (self, self.accept())

class proxy_sender (asynchat.async_chat):

	"Sends data to the server"

	def __init__ (self, receiver, address):
		asynchat.async_chat.__init__ (self)
		self.receiver = receiver
		self.set_terminator (None)
		self.create_socket (socket.AF_INET, socket.SOCK_STREAM)
		self.buffer = ''
		self.set_terminator ('\n')
		self.connect (address)
	
	def handle_connect (self):
		print 'Sender connected'
	
	def collect_incoming_data (self, data):
		self.buffer = self.buffer + data
	
	def found_terminator (self):
		data = self.buffer
		self.buffer = ''
		print '==> (%d) %s' % (self.id, repr(data))
		self.receiver.push (data + '\n')
	
	def handle_close (self):
		print 'Sender closing (inbuf len %d (%s), ac_in %d, ac_out %d )' % (
			len( self.buffer ),
			self.buffer,
			len( self.ac_in_buffer ),
			len( self.ac_out_buffer )
			)

		if len( self.buffer ):
			self.found_terminator()
			
		self.receiver.close_when_done()
		self.close()

class proxy_receiver (asynchat.async_chat):

	"Receives data from the caller"

	channel_counter = 0
	
	def __init__ (self, server, (conn, addr)):
		asynchat.async_chat.__init__ (self, conn)
		self.set_terminator ('\n')
		self.server = server
		self.id = self.channel_counter
		self.channel_counter = self.channel_counter + 1
		self.sender = proxy_sender (self, server.there)
		self.sender.id = self.id
		self.buffer = ''
	
	def collect_incoming_data (self, data):
		self.buffer = self.buffer + data
		
	def found_terminator (self):
		import re
		data = re.sub( r'\:8080', '', self.buffer )
		data = re.sub( r'localhost', self.server.there[0], data )
		self.buffer = ''
		print '<== (%d) %s' % (self.id, repr(data))
		self.sender.push (data + '\n')
	
	def handle_close (self):
		print 'Receiver closing (inbuf len %d (%s), ac_in %d, ac_out %d )' % (
			len( self.buffer ),
			self.buffer,
			len( self.ac_in_buffer ),
			len( self.ac_out_buffer )
			)

		if len( self.buffer ):
			self.found_terminator()
		
		self.sender.close_when_done()
		self.close()

if __name__ == '__main__':
	import sys
	import string
	if len(sys.argv) < 3:
		print 'Usage: %s <server-host> <server-port>' % sys.argv[0]
	else:
		ps = proxy_server (sys.argv[1], string.atoi (sys.argv[2]))
		asyncore.loop()
