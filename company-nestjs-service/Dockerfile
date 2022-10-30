FROM node:18-alpine

WORKDIR /usr/

COPY ./ /usr/

RUN cd /usr/ && npm install && npm run build

EXPOSE 3000

CMD [ "npm", "run", "start:dev" ]
