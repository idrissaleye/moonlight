FROM ubuntu
RUN apt-get update 
RUN apt install -y mysql-server apache2 apache2-utils curl php net-tools libapache2-mod-php python3-pip python3.10-venv bind9 bind-utils
EXPOSE 80 11434 5000 443 3306
CMD echo "ServerName localhost" >> /etc/apache2/apache2.conf && /etc/init.d/apache2 restart && bash
COPY Moonlight/* /var/www/html
RUN pip install -r /var/www/html/requirements.txt
COPY ollama-linux-amd64.tgz /usr
RUN tar -C /usr -xzf /usr/ollama-linux-amd64.tgz
RUN ollama serve
RUN useradd -r -s /bin/false -U -m -d /usr/share/ollama ollama
RUN usermod -a -G ollama $(whoami)
RUN echo -e "[Unit]\nDescription=Ollama Service\nAfter=network-online.target\n\n[Service]\nExecStart=/usr/bin/ollama serve\nUser=ollama\nGroup=ollama\nRestart=always\nRestartSec=3\nEnvironment=\"PATH=\$PATH\"\n\n[Install]\nWantedBy=multi-user.target" > /etc/systemd/system/ollama.service
RUN systemctl daemon-reload
RUN systemctl enable ollama
CMD ollama run deepseek-r1:1.5b



#comment