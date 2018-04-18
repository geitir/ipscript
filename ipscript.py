
import socket
import urllib.parse
import urllib.request
from time import sleep

base_url = "127.0.0.1"

pi_number = 999

sleep_time = 1800
timeout = 5
url_error_wait = 10

talk = False
loop = True

print("starting ipscript.py")

url = base_url + "ipcollect.php" + "?{0}"

if pi_number == 999:
	print("warning: pi_number is set to default value of 999")

connection_problem = False
while 1:

	try:
		local_ip = ((([ip for ip in socket.gethostbyname_ex(socket.gethostname())[2] if not ip.startswith("127.")] or [[(s.connect(("8.8.8.8", 53)), s.getsockname()[0], s.close()) for s in [socket.socket(socket.AF_INET, socket.SOCK_DGRAM)]][0][1]]) + ["no IP found"])[0])
	except socket.error as e:
		connection_problem = True
		print("URLError:", e)
		print("waiting {0} seconds and trying again...".format(url_error_wait))
		sleep(url_error_wait)
		if not url_error_wait >= sleep_time:
			url_error_wait += url_error_wait
		continue

	if connection_problem:
		connection_problem = False
		print("reconnected")

	if talk:
		print("sending: pi_number : {0} local_ip : {1}".format(pi_number, local_ip))

	params = urllib.parse.urlencode({"pi_number" : pi_number, "local_ip" : local_ip})
	
	try:
		response = urllib.request.urlopen(url.format(params), None, timeout)
	except (urllib.request.URLError, socket.error) as e:
		connection_problem = True
		print("URLError:", e)
		print("waiting {0} seconds and trying again...".format(url_error_wait))
		sleep(url_error_wait)
		if not url_error_wait >= sleep_time:
			url_error_wait += url_error_wait
		continue
	except Exception as e:
		raise Exception("ipscript.py failed: urllib failed: {0}".format(e))
	
	if connection_problem:
		connection_problem = False
		print("reconnected")

	if talk:
		print("response code : {0}".format(response.getcode()))
		print("response text : {0}".format(response.read().decode("utf-8")))
	response.close()

	if not loop:
		break
	sleep(sleep_time)



