class HttpClient {
    constructor() {
        this.http = new XMLHttpRequest();
    }

    async request(method, url, data, asXML) {
        return new Promise((resolve, reject) => {
            this.http.open(method, url, true);

            if (method === 'POST' || method === 'PUT') {
                if (data instanceof FormData) {
                    this.http.setRequestHeader("Content-type","multipart/form-data; boundary='--------------------------'");
                } else {
                    this.http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                }
            }

            this.http.onload = () => {
                if (!asXML) {
                    try {
                        const responseHeaders = this.http.getAllResponseHeaders();
                        const headersArray = responseHeaders.trim().split('\n');
                        const headers = {};

                        headersArray.forEach(header => {
                            const [name, value] = header.split(':');
                            headers[name.trim()] = value.trim();
                        });

                        const responseData = {
                            headers,
                            body: this.http.responseText,
                        };

                        resolve(responseData);
                        resolve(this.http.responseText);
                    } catch (e) {
                        reject(e);
                    }
                } else {
                    resolve(this.http.responseXML);
                }
            };

            this.http.onerror = () => {
                reject(`Network Error`);
            };

            if (data instanceof FormData) {
                this.http.send(data);
            } else {
                const params = new URLSearchParams(data).toString();
                this.http.send(params);
            }
        });
    }

    async get(url, data, asXML) {
        return this.request('GET', url, data, asXML);
    }

    async post(url, data, asXML) {
        return this.request('POST', url, data, asXML);
    }

    async put(url, data, asXML) {
        return this.request('PUT', url, data, asXML);
    }

    async delete(url, data, asXML) {
        return this.request('DELETE', url, data, asXML);
    }

    async uploadFile(url, file, asXML) {
        return new Promise((resolve, reject) => {
            const formData = new FormData();
            formData.append("fileToUpload", file);

            this.http.open("POST", url, true);

            this.http.onload = () => {
                if (!asXML) {
                    try {
                        resolve(this.http.responseText);
                    } catch (e) {
                        reject(e);
                    }
                } else {
                    resolve(this.http.responseXML);
                }
            };

            this.http.onerror = () => {
                reject(new Error("Fetch error"));
            };

            this.http.send(formData);
        });
    }
}