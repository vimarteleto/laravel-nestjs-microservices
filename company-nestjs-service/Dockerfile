FROM node:18-alpine

WORKDIR /app

RUN npm i -g @nestjs/cli

COPY package*.json ./

RUN npm install

COPY . .

CMD [ "npm", "run", "start:dev" ]
