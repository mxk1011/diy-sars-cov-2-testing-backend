# EPILAMP

EpiLAMP is a software to manage decentralized infection testing. The goal is to Provide a cheap and reliable way of increasing mass testing, which can be implemented fast in a decentralized way. More information:

https://pad.riseup.net/p/DIY-SARS-CoV-2-testing

## How to start the backend

The backend is written in Laravel / PHP. You can start it locally without installing PHP on your computer by using docker. Just do

`./vessel up`

with your console. To jump into the docker container open a new terminal tab and run 

`./vessel exec app bash`

To install all dependencies, run in the docker container:

`composer install`

Then you can use the backend!

## How will it be deployed?

The software is based on a stateless RESTApi. This means, it can scale horizontally without limitations. The lead developer recommend using Kubernetes for the live hosting

## Developers

Lead Developer Backend:
Maximilian Schmidt (m.schmidt@cpitech.io)
