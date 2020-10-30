The map pathfinding is performed by a Java servlet. The computer on which the Tomcat server is currently running must be specified in map/getpath.php.


To do:
* Title
* Change money to balance

Low priority:
* Change ship name
* Spawning food
* Hover over ship - show name



Initial things:
Balance: 1000
Crew: 1
Ship: Pirate
Food: 250
Weapons: 5

Max food: 1000
Max weapons: 50
Max crew: 50





Variable to decide winner of battle:
Number of crew, amount of weapons, type of ship, health of ship

Pirate: 0.6
Cruise: 0.8
Battle: 1

crew/max_crew * weapons/max_weapons * health * type of ship

P(A)
P(B)

If difference between P(A) and P(B) is <0.3, choose randomly between A and B as winner






CHANGING LAND FROM SEA AND VICE-VERSA

SHOULD BE LAND

20,16
19,17
19,16
41,6
39,6
39,5
40,5
7,4
8,4
9,4
10,4
11,4
12,4
13,4
16.4
11,3
12,3
13,3
14,3
15,3
16,3
17,3
18,3
19,3
20,3
21,3
22,3
23,3
13,4
12,4
11,4
10,4
21,4
12,2
13,2
14,2
15,2
16,2
17,2
18,2
19,2
20,2
21,2
18,1
19,1
20,1
21,1
22,1
23,1
24,1
25,1
26,1
27,1
28,1
29,1
30,1
31,1
32,1
33,1
34,1
35,1
22,2
23,2
24,2
25,2
26,2
27,2
28,2
29,2
30,2
31,2
32,2
33,2
40,2
41,2
42,2
43,2
1,5
2,5
3,5
18,5
19,5
20,5
21,5
22,5
23,5
24,5
21,6
20,7
21,7
3,6
4,6
5,6
6,6
7,6
8,6
9,6
10,7
11,8
24,8
25,8
25,7
26,8
26,9
14,13
15,14
21,4
22,14
22,16
23,16
14,16
20,17
17,14
20,19
21,21
21,22
22,23
22,24
22,25
21,27
21,28
21,29
21,30
25,32
25,33
26,32
35,33
36,33
42,33
48,32
49,32
55,32
58,32
59,32
69,33
70,33
71,33
72,33
72,34
73,34
73,28
74,27
74,26
73,27
73,28
72,28
68,27
69,19
71,20
65,11
65,12
70,8
70,7
71,7
71,6
72,6
73,6
67,9
67,8
67,7
66,7
67,6
68,6
69,6
70,6
60,2
59,2
58,2
57,2
58,16
61,2
62,3
61,3
63,3
64,3
65,3
59,2
49,3
48,3
50,3
50,2
51,2
52,2
52,3
53,3
54,3
55,3
50,4
51,4
48,4
46,4
45,4
44,4
42,5
45,5
42,6
41,6
41,7
41,5
40,5
42,4
39,5
39,6
32,5
33,5
34,5
42,26
41,25
40,24
20,23
39,19
39,18
35,12
34,12
34,13
34,16
22,16
23,16
24,16
22,16
48,10
48,9
45,9
44,9
34,13

SHOULD BE SEA
36,11
37,11




// 1 is land
// 2 is water

myterrain[20][16]  = 1;
myterrain[19][17]  = 1;
myterrain[19][16]  = 1;
myterrain[41][6]  = 1;
myterrain[39][6]  = 1;
myterrain[39][5]  = 1;
myterrain[40][5]  = 1;
myterrain[7][4]  = 1;
myterrain[8][4]  = 1;
myterrain[9][4]  = 1;
myterrain[10][4]  = 1;
myterrain[11][4]  = 1;
myterrain[12][4]  = 1;
myterrain[13][4]  = 1;
myterrain[16.4]  = 1;
myterrain[11][3]  = 1;
myterrain[12][3]  = 1;
myterrain[13][3]  = 1;
myterrain[14][3]  = 1;
myterrain[15][3]  = 1;
myterrain[16][3]  = 1;
myterrain[17][3]  = 1;
myterrain[18][3]  = 1;
myterrain[19][3]  = 1;
myterrain[20][3]  = 1;
myterrain[21][3]  = 1;
myterrain[22][3]  = 1;
myterrain[23][3]  = 1;
myterrain[13][4]  = 1;
myterrain[12][4]  = 1;
myterrain[11][4]  = 1;
myterrain[10][4]  = 1;
myterrain[21][4]  = 1;
myterrain[12][2]  = 1;
myterrain[13][2]  = 1;
myterrain[14][2]  = 1;
myterrain[15][2]  = 1;
myterrain[16][2]  = 1;
myterrain[17][2]  = 1;
myterrain[18][2]  = 1;
myterrain[19][2]  = 1;
myterrain[20][2]  = 1;
myterrain[21][2]  = 1;
myterrain[18][1]  = 1;
myterrain[19][1]  = 1;
myterrain[20][1]  = 1;
myterrain[21][1]  = 1;
myterrain[22][1]  = 1;
myterrain[23][1]  = 1;
myterrain[24][1]  = 1;
myterrain[25][1]  = 1;
myterrain[26][1]  = 1;
myterrain[27][1]  = 1;
myterrain[28][1]  = 1;
myterrain[29][1]  = 1;
myterrain[30][1]  = 1;
myterrain[31][1]  = 1;
myterrain[32][1]  = 1;
myterrain[33][1]  = 1;
myterrain[34][1]  = 1;
myterrain[35][1]  = 1;
myterrain[22][2]  = 1;
myterrain[23][2]  = 1;
myterrain[24][2]  = 1;
myterrain[25][2]  = 1;
myterrain[26][2]  = 1;
myterrain[27][2]  = 1;
myterrain[28][2]  = 1;
myterrain[29][2]  = 1;
myterrain[30][2]  = 1;
myterrain[31][2]  = 1;
myterrain[32][2]  = 1;
myterrain[33][2]  = 1;
myterrain[40][2]  = 1;
myterrain[41][2]  = 1;
myterrain[42][2]  = 1;
myterrain[43][2]  = 1;
myterrain[1][5]  = 1;
myterrain[2][5]  = 1;
myterrain[3][5]  = 1;
myterrain[18][5]  = 1;
myterrain[19][5]  = 1;
myterrain[20][5]  = 1;
myterrain[21][5]  = 1;
myterrain[22][5]  = 1;
myterrain[23][5]  = 1;
myterrain[24][5]  = 1;
myterrain[21][6]  = 1;
myterrain[20][7]  = 1;
myterrain[21][7]  = 1;
myterrain[3][6]  = 1;
myterrain[4][6]  = 1;
myterrain[5][6]  = 1;
myterrain[6][6]  = 1;
myterrain[7][6]  = 1;
myterrain[8][6]  = 1;
myterrain[9][6]  = 1;
myterrain[10][7]  = 1;
myterrain[11][8]  = 1;
myterrain[24][8]  = 1;
myterrain[25][8]  = 1;
myterrain[25][7]  = 1;
myterrain[26][8]  = 1;
myterrain[26][9]  = 1;
myterrain[14][13]  = 1;
myterrain[15][14]  = 1;
myterrain[21][4]  = 1;
myterrain[22][14]  = 1;
myterrain[22][16]  = 1;
myterrain[23][16]  = 1;
myterrain[14][16]  = 1;
myterrain[20][17]  = 1;
myterrain[17][14]  = 1;
myterrain[20][19]  = 1;
myterrain[21][21]  = 1;
myterrain[21][22]  = 1;
myterrain[22][23]  = 1;
myterrain[22][24]  = 1;
myterrain[22][25]  = 1;
myterrain[21][27]  = 1;
myterrain[21][28]  = 1;
myterrain[21][29]  = 1;
myterrain[21][30]  = 1;
myterrain[25][32]  = 1;
myterrain[25][33]  = 1;
myterrain[26][32]  = 1;
myterrain[35][33]  = 1;
myterrain[36][33]  = 1;
myterrain[42][33]  = 1;
myterrain[48][32]  = 1;
myterrain[49][32]  = 1;
myterrain[55][32]  = 1;
myterrain[58][32]  = 1;
myterrain[59][32]  = 1;
myterrain[69][33]  = 1;
myterrain[70][33]  = 1;
myterrain[71][33]  = 1;
myterrain[72][33]  = 1;
myterrain[72][34]  = 1;
myterrain[73][34]  = 1;
myterrain[73][28]  = 1;
myterrain[74][27]  = 1;
myterrain[74][26]  = 1;
myterrain[73][27]  = 1;
myterrain[73][28]  = 1;
myterrain[72][28]  = 1;
myterrain[68][27]  = 1;
myterrain[69][19]  = 1;
myterrain[71][20]  = 1;
myterrain[65][11]  = 1;
myterrain[65][12]  = 1;
myterrain[70][8]  = 1;
myterrain[70][7]  = 1;
myterrain[71][7]  = 1;
myterrain[71][6]  = 1;
myterrain[72][6]  = 1;
myterrain[73][6]  = 1;
myterrain[67][9]  = 1;
myterrain[67][8]  = 1;
myterrain[67][7]  = 1;
myterrain[66][7]  = 1;
myterrain[67][6]  = 1;
myterrain[68][6]  = 1;
myterrain[69][6]  = 1;
myterrain[70][6]  = 1;
myterrain[60][2]  = 1;
myterrain[59][2]  = 1;
myterrain[58][2]  = 1;
myterrain[57][2]  = 1;
myterrain[58][16]  = 1;
myterrain[61][2]  = 1;
myterrain[62][3]  = 1;
myterrain[61][3]  = 1;
myterrain[63][3]  = 1;
myterrain[64][3]  = 1;
myterrain[65][3]  = 1;
myterrain[59][2]  = 1;
myterrain[49][3]  = 1;
myterrain[48][3]  = 1;
myterrain[50][3]  = 1;
myterrain[50][2]  = 1;
myterrain[51][2]  = 1;
myterrain[52][2]  = 1;
myterrain[52][3]  = 1;
myterrain[53][3]  = 1;
myterrain[54][3]  = 1;
myterrain[55][3]  = 1;
myterrain[50][4]  = 1;
myterrain[51][4]  = 1;
myterrain[48][4]  = 1;
myterrain[46][4]  = 1;
myterrain[45][4]  = 1;
myterrain[44][4]  = 1;
myterrain[42][5]  = 1;
myterrain[45][5]  = 1;
myterrain[42][6]  = 1;
myterrain[41][6]  = 1;
myterrain[41][7]  = 1;
myterrain[41][5]  = 1;
myterrain[40][5]  = 1;
myterrain[42][4]  = 1;
myterrain[39][5]  = 1;
myterrain[39][6]  = 1;
myterrain[32][5]  = 1;
myterrain[33][5]  = 1;
myterrain[34][5]  = 1;
myterrain[42][26]  = 1;
myterrain[41][25]  = 1;
myterrain[40][24]  = 1;
myterrain[20][23]  = 1;
myterrain[39][19]  = 1;
myterrain[39][18]  = 1;
myterrain[35][12]  = 1;
myterrain[34][12]  = 1;
myterrain[34][13]  = 1;
myterrain[34][16]  = 1;
myterrain[22][16]  = 1;
myterrain[23][16]  = 1;
myterrain[24][16]  = 1;
myterrain[22][16]  = 1;
myterrain[48][10]  = 1;
myterrain[48][9]  = 1;
myterrain[45][9]  = 1;
myterrain[44][9]  = 1;
myterrain[34][13]  = 1;

myterrain[36][11] = 2;
myterrain[37][11] = 2;


