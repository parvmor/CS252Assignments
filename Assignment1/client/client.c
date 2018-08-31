#include <assert.h>
#include <stdio.h>
#include <sys/socket.h>
#include <netinet/in.h>
#include <string.h>
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
    int clientSocket;
    char buffer[1024];
    struct sockaddr_in serverAddr;
    socklen_t addr_size;

    clientSocket = socket(PF_INET, SOCK_STREAM, 0);

    serverAddr.sin_family = AF_INET;
    serverAddr.sin_port = htons(5432);
    serverAddr.sin_addr.s_addr = inet_addr("127.0.0.1");
    memset(serverAddr.sin_zero, '\0', sizeof serverAddr.sin_zero);

    addr_size = sizeof serverAddr;
    connect(clientSocket, (struct sockaddr *) &serverAddr, addr_size);

    recv(clientSocket, buffer, 1024, 0);

    char input[1024];
    scanf("%s", input);
    int n = strlen(input);
    int count[4] = {0, 0, 0, 0};
    // dogs, cats, cars, trucks

    for (int i = 0; i < n; i++) {
        if (input[i] == ' ' || input[i] == '\t') {
            continue;
        }
        assert(input[i] >= '0' && input[i] <= '9');
        int j = i;
        while (j < n && input[j] >= '0' && input[j] <= '9') {
            j++;
        }
        int cnt = parse_int(input, i, j);
        while (j < n && (input[j] == ' ' || input[j] < '\t')) {
            j++;
        }
        int ind = get_ind(input, &j, n);
        count[ind] = cnt;

        i = j - 1;
    }

    printf("Data received: %s",buffer);

    return 0;
}
