#!/bin/bash
curl -d "tx_id=$1" -X POST http://localhost/transactions/xmr
