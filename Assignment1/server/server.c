#include <stdio.h>
#include <stdlib.h>
#include <unistd.h>
#include <netinet/in.h>
#include <string.h>
#include <sys/socket.h>
#include <arpa/inet.h>

int parse_int(char* s, int start, int end) {
    int base = 1;
    int ret = 0;
    while (end > start) {
        ret += base * (s[end - 1] - '0');
        base *= 10;
        end--;
    }
    return ret;
}

int get_ind(char* s, int* ind, int n) {
    int ret = 0;
    if (s[*ind] == 'd') {
        ret = 0;
    } else if (s[*ind] == 't') {
        ret = 3;
    } else if (s[*ind] == 'c') {
        if (s[*ind + 2] == 'r') {
            ret = 2;
        } else {
            ret = 1;
        }
    }
    while (*ind < n && ((s[*ind] >= 'a' && s[*ind] <= 'z') || (s[*ind] >= 'A' && s[*ind] <= 'Z'))) {
        *ind = *ind + 1;
    }
    return ret;
}

int main(){
    int welcomeSocket, newSocket;
    char buffer[1024];
    struct sockaddr_in serverAddr;
    struct sockaddr_storage serverStorage;
    socklen_t addr_size;

    welcomeSocket = socket(PF_INET, SOCK_STREAM, 0);

    serverAddr.sin_family = AF_INET;
    serverAddr.sin_port = htons(5432);
    serverAddr.sin_addr.s_addr = inet_addr("127.0.0.1");
    memset(serverAddr.sin_zero, '\0', sizeof serverAddr.sin_zero);

    bind(welcomeSocket, (struct sockaddr *) &serverAddr, sizeof(serverAddr));

    if(listen(welcomeSocket,5)==0)
        printf("I'm listening\n");
    else
        printf("Error\n");

    while (1) {
        addr_size = sizeof serverStorage;
        newSocket = accept(welcomeSocket, (struct sockaddr *) &serverStorage, &addr_size);
        int valread = read(newSocket, buffer, 1024);
        int n = strlen(buffer);

        int count[4] = {0, 0, 0, 0};
        // dogs, cats, cars, trucks

        for (int i = 0; i < n; i++) {
            if (buffer[i] == ' ' || buffer[i] == '\t' || buffer[i] == '\n') {
                continue;
            }
            int j = i;
            if (!(buffer[j] >= '0' && buffer[j] <= '9')) {
                while (j < n && !(buffer[j] >= '0' && buffer[j] <= '9')) {
                    j++;
                }
                i = j - 1;
                continue;
            }
            while (j < n && buffer[j] >= '0' && buffer[j] <= '9') {
                j++;
            }
            int cnt = parse_int(buffer, i, j);
            while (j < n && (buffer[j] == ' ' || buffer[j] == '\t')) {
                j++;
            }
            int ind = get_ind(buffer, &j, n);
            printf("%d %d\n", ind, cnt);
            count[ind] = cnt;

            i = j - 1;
        }

        for (int i = 0; i < 4; i++) {
            printf("%d %d\n", i, count[i]);
        }

        char files[1024];
        files[0] = 'i'; files[1] = 'm'; files[2] = 'a'; files[3] = 'g'; files[4] = 'e'; files[5] = 's'; files[6] = '/';
        for (int i = 0; i < 4; i++) {
            int offset;
            if (i == 0) {
                files[7] = 'd'; files[8] = 'o'; files[9] = 'g';
                offset = 10;
            } else if (i == 1) {
                files[7] = 'c'; files[8] = 'a'; files[9] = 't';
                offset = 10;
            } else if (i == 2) {
                files[7] = 'c'; files[8] = 'a'; files[9] = 'r';
                offset = 10;
            } else {
                files[7] = 't'; files[8] = 'r'; files[9] = 'u'; files[10] = 'c'; files[11] = 'k';
                offset = 12;
            }
            files[offset++] = '/';
            buffer[0] = '0' + count[i]; buffer[1] = '\0';
            send(newSocket, buffer, 4096, 0);
            for (int j = 0; j < count[i]; j++) {
                files[offset] = '1' + j; files[offset+1] = '.'; files[offset+2] = 'j'; files[offset+3] = 'p'; files[offset+4] = 'g'; files[offset+5] = '\0';
                printf("Sending file: %s\n", files);

                FILE* fp = fopen(files, "rb");
                if (fp == NULL) {
                    continue;
                }
                while (1) {
                    unsigned char buffer[4096] = {0};
                    int nread = fread(buffer, 1, 4096, fp);
                    if (nread > 0) {
                        send(newSocket, buffer, 4096, 0);
                    }
                    if (nread < 4096) {
                        break;
                    }
                }
                fclose(fp);

                buffer[0] = 's'; buffer[1] = 't'; buffer[2] = 'a'; buffer[3] = 'r'; buffer[4] = 't'; buffer[5] = '\0';
                send(newSocket, buffer, 4096, 0);
            }
        }

        close(newSocket);
        printf("Connection closed.\n");
    }

    return 0;
}
