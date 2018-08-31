#include <stdio.h>
#include <stdlib.h>
#include <unistd.h>
#include <sys/socket.h>
#include <netinet/in.h>
#include <string.h>
#include <arpa/inet.h>

int main(){
    int clientSocket;
    char buffer[4096];
    struct sockaddr_in serverAddr;
    socklen_t addr_size;

    clientSocket = socket(PF_INET, SOCK_STREAM, 0);

    serverAddr.sin_family = AF_INET;
    serverAddr.sin_port = htons(5432);
    serverAddr.sin_addr.s_addr = inet_addr("127.0.0.1");
    memset(serverAddr.sin_zero, '\0', sizeof serverAddr.sin_zero);

    addr_size = sizeof serverAddr;
    connect(clientSocket, (struct sockaddr *) &serverAddr, addr_size);

    char input[1024];
    fgets(input, 1024, stdin);
    int n = strlen(input);
    send(clientSocket, input, strlen(input) + 1, 0);
    printf("Message sent\n");

    char files[1024];
    files[0] = 'i'; files[1] = 'm'; files[2] = 'a'; files[3] = 'g'; files[4] = 'e'; files[5] = 's'; files[6] = '/';
    int offset;
    int bytes = 0;
    int state = 0;
    int count = 0;
    int type = 0;
    while (type < 4) {
        if (state == 0) {
            bytes = read(clientSocket, buffer, 4096);
            count = atoi(buffer);
            if (count == 0) {
                type++;
                continue;
            }
            if (type == 0) {
                files[7] = 'd'; files[8] = 'o'; files[9] = 'g';
                offset = 10;
            } else if (type == 1) {
                files[7] = 'c'; files[8] = 'a'; files[9] = 't';
                offset = 10;
            } else if (type == 2) {
                files[7] = 'c'; files[8] = 'a'; files[9] = 'r';
                offset = 10;
            } else if (type == 3) {
                files[7] = 't'; files[8] = 'r'; files[9] = 'u'; files[10] = 'c'; files[11] = 'k';
                offset = 12;
            } else {
                break;
            }
            state = 1;
        } else {
            for (int i = 0; i < count; i++) {
                bytes = read(clientSocket, buffer, 4096);
                files[offset] = '0' + i; files[offset+1] = '.'; files[offset+2] = 'j'; files[offset+3] = 'p'; files[offset+4] = 'g'; files[offset+5] = '\0';
                printf("Writing to file: %s\n", files);
                FILE* fp = fopen(files, "wb");
                if (fp == NULL) {
                    exit(EXIT_FAILURE);
                }
                while (strcmp(buffer, "start")) {
                    fwrite(buffer, 1, bytes, fp);
                    bytes = read(clientSocket, buffer, 4096);
                }
                fclose(fp);
            }
            state = 0;
            type++;
        }
    }

    return 0;
}
