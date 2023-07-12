
-- Update 'aanmelding_betaald_bedrag' by subtracting the €750 the users paid less as they rented the product
UPDATE `stem_aanmeldingen` SET aanmelding_betaald_bedrag= aanmelding_betaald_bedrag - 750 WHERE aanmelding_ID IN (9357,9359,9457,9461,9459,9519,9525,9529,9545,9561,9571,9599,9605,9613,9631,9637,9667,9683,9715,9737,9751,9769,9803,9831,9835,9841,9843,9845,9847)
