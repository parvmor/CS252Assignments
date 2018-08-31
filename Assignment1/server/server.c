#include <stdio.h>
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

    addr_size = sizeof serverStorage;
    newSocket = accept(welcomeSocket, (struct sockaddr *) &serverStorage, &addr_size);

    while (1) {
        int valread = read(newSocket, buffer, 1024);
        int n = strlen(buffer);

        int count[4] = {0, 0, 0, 0};
        // dogs, cats, cars, trucks

        for (int i = 0; i < n; i++) {
            if (buffer[i] == ' ' || buffer[i] == '\t') {
                continue;
            }
            int j = i;
            while (j < n && buffer[j] >= '0' && buffer[j] <= '9') {
                j++;
            }
            int cnt = parse_int(buffer, i, j);
            while (j < n && (buffer[j] == ' ' || buffer[j] < '\t')) {
                j++;
            }
            int ind = get_ind(buffer, &j, n);
            count[ind] = cnt;

            i = j - 1;
        }

        char files[1024];
        files[0] = 'i';
        files[1] = 'm';
        files[2] = 'a';
        files[3] = 'g';
        files[4] = 'e';
        files[5] = 's';
        files[6] = '/';
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
                files[7] = 't'; files[8] = 'r'; files[9] = 'u';
                files[10] = 'c'; files[11] = 'k';
                offset = 12;
            }
            files[offset++] = '/';
            for (int j = 0; j < count[i]; j++) {
                files[offset++] = '1' + j;
                files[offset++] = '.';
                files[offset++] =  'j';
                files[offset++] =  'p';
                files[offset++] =  'q';
                files[offset++] = '\0';

                FILE* fp = fopen(files, "rb");
                if (fp == NULL) {
                    continue;
                }

                while (1) {
                    unsigned char buffer[4096] = {0};
                    int nread = fread(buffer, 1, 4096, fp);
                    if (nread > 0) {
                        send(newSocket, buffer, nread, 0);
                    }

                    if (nread < 1024) {
                        break;
                    }
                }
            }
        }
    }

    return 0;
}
