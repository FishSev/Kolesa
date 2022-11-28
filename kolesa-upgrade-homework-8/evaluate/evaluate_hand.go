package evaluate

import (
	"sort"
	"strings"
)

type card struct {
	face byte
	suit byte
}

const faces = "23456789tjqka"
const suits = "shdc"

func isStraight(cards []card) bool {
	sorted := make([]card, 5)
	copy(sorted, cards)
	sort.Slice(sorted, func(i, j int) bool {
		return sorted[i].face < sorted[j].face
	})
	if sorted[0].face+4 == sorted[4].face {
		return true
	}
	if sorted[4].face == 14 && sorted[0].face == 2 && sorted[3].face == 5 {
		return true
	}
	return false
}

func isFlush(cards []card) bool {
	suit := cards[0].suit
	for i := 1; i < 5; i++ {
		if cards[i].suit != suit {
			return false
		}
	}
	return true
}

func AnalyzeHand(hand string) string {
	temp := strings.Fields(strings.ToLower(hand))
	splitSet := make(map[string]bool)
	var split []string
	for _, s := range temp {
		if !splitSet[s] {
			splitSet[s] = true
			split = append(split, s)
		}
	}
	if len(split) != 5 {
		return "Invalid hand"
	}
	var cards []card

	for _, s := range split {
		if len(s) != 2 {
			return "Invalid hand"
		}
		fIndex := strings.IndexByte(faces, s[0])
		if fIndex == -1 {
			return "Invalid hand"
		}
		sIndex := strings.IndexByte(suits, s[1])
		if sIndex == -1 {
			return "Invalid hand"
		}
		cards = append(cards, card{byte(fIndex + 2), s[1]})
	}

	groups := make(map[byte][]card)
	for _, c := range cards {
		groups[c.face] = append(groups[c.face], c)
	}

	switch len(groups) {
	case 2:
		for _, group := range groups {
			if len(group) == 4 {
				return "(7) Four of a kind"
			}
		}
		return "(6) Full house"
	case 3:
		for _, group := range groups {
			if len(group) == 3 {
				return "(3) Three of a kind"
			}
		}
		return "(2) Two pairs"
	case 4:
		return "(1) One pair"
	default:
		flush := isFlush(cards)
		straight := isStraight(cards)
		switch {
		case flush && straight:
			return "(8) Straight flush"
		case flush:
			return "(5) Flush"
		case straight:
			return "(4) Straight"
		default:
			return "High card"
		}
	}
}
